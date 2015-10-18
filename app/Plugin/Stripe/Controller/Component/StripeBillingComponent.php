<?
# Stripe may take 7 days to pay, but at least they can deposit into a personal checking account
# Braintree pays out in 3 days but requires a business account, requiring monthly fees, business license ($), etc...
#
App::import("Vendor", "Stripe.Stripe", array('file'=>"Stripe/init.php"));

class StripeBillingComponent extends Component
{
	public $config = null;
	#public $components = array('Stripe.Stripe'); # Indirectly, if not in controller, startup() not called =(
	# XXX TODO
	#public $uses = array('Stripe.StripeBilling');

	function startup(Controller $controller)
	{
		$this->controller = $controller;
		Configure::load("Stripe.billing"); 
		$this->config = Configure::read("Billing");
		if(!($secret  = Configure::read("Stripe.Secret")))
		{
			throw new InternalServerException("Stripe secret api key not configured");
		}
		\Stripe\Stripe::setApiKey($secret);

		return parent::startup($controller);
	}

	function defaultCard($custId=null)
	{
		if(empty($custId))
		{
			$custId = $this->controller->site('stripe_id'); # Existing...
		}
		$customer = $this->customer($custId);
		$defaultCardId = !empty($customer['default_source']) ? $customer['default_source'] : null;
		if(!empty($customer['sources']['data']))
		{
			$cards = $customer['sources']['data'];
			if(empty($defaultCardId))  { $defaultCardId  = $cards[0]['id']; } # First.
			foreach($cards as $card) { 
				if($card['id'] == $defaultCardId)
				{
					return $card;
				}
			}
		}
		return null;
	}

	# Create hosting subscription.
	function hostingSubscription($data=null) # Plan is passed...
	{
		# Maybe someday simplify...
		$custId = $this->controller->site('stripe_id'); # Existing...
		$subId = $this->controller->site('subscription_id'); # Existing...
		if(!empty($data))
		{
			$data['description'] = $this->controller->site("title") . " / ".$this->controller->site("hostname").".hopefulpress.com";

			# Clean  up card if source (token) provided.
			#if(!empty($data['source'])) { unset($data['card']); } # 

			$plan = $this->plan($data['plan']); # Make sure plan exists.
			if(is_string($plan))
			{
				return $plan;
			}
		}

		# Passing plan to the customer object will ADD a new subscription, NOT replace it...

		$customer = $this->customer($custId,$data); # Create if needed.
		if(is_string($customer))
		{
			return $customer;
		}
		$custId = $customer['id'];
		$this->controller->site('stripe_id',$custId); # Save...

		$sub = $this->subscription($customer,$subId,array('plan'=>$data['plan']));
		if(is_string($sub))
		{
			return $sub;
		} else { # Save 
			$subId = $sub['id'];
			$this->controller->site('subscription_id',$subId); # Save...
		}
		return $sub;  # Need to check is_string()
		# Sometimes we want the subscription itself assuming customer.
	}


	# Cancel subscription
	function cancelHostingSubscription() # Hosting.
	{
		$custId = $this->controller->site('stripe_id'); # Existing...
		$subId = $this->controller->site('subscription_id'); # Existing...
		$subscription = $this->cancelSubscription($custId,$subId);
		if(is_string($subscription))
		{
			return "Could not cancel billing: $subscription";
		} 
		return; # OK
	}

	function validHostingSubscription()
	{
		$custId = $this->controller->site('stripe_id'); # Existing...
		$subId = $this->controller->site('subscription_id'); # Existing...
		return $this->validSubscription($custId,$subId);
	}

	# Subscription for yearly domain registration
	function saveYearlyDomainSubscription($data)
	{
		# Charge card on file.... assumes already there but will barf if not found.
		$customer_id = $this->controller->site("stripe_id");

		$data['description'] = "Yearly Domain Registration - (".$this->controller->site("title") . "; ".$this->controller->site("hostname").".hopefulpress.com) - {$data['domain']}";

		$customer = $this->Stripe->customerRetrieve($customer_id);
		if(is_string($customer)) {
			return "Unable to process your payment: $customer";
		} else if(empty($customer) || !empty($customer['deleted'])) {
			return "Billing information not found";
		}

		$plan = $this->plan('domain_registration',$this->config['domain_registration']);
		if(is_string($plan))
		{
			return "Couldn't initialize payment plan for domain registration";
		} else {
			return $this->updateSubscription($customer_id,$this->config['plans']['id']);
		}
		# XXX TODO make sure this doesnt accidentally remove monthly hosting.
	}
	
	# Subscription for monthly/yearly hosting

	##################################################################
	# WRAPPERS THAT CATCH EXCEPTIONS AND RETURN ERROR MESSAGE STRINGS

	function plan($params) # Add if needed.
	{
		$name = is_array($params) ? $params['id'] : $params;
		$details = is_array($params) ? $params : null;

		# Try to find plan details in our system. Under 'plans', 'yearlyPlans' or as a direct key in $config['Billing']
		# In case we need to add details.
		if(empty($details))
		{
			if(!($details = Configure::read("Billing.plans.$name")))
			{
				if(!($details = Configure::read("Billing.yearlyPlans.$name")))
				{
					if(!($details = Configure::read("Billing.$name")))
					{
						return "Could not find plan details";
					}
				}
			}

		}
		
		try
		{
			try {
				# If plan does not exist, add. Otherwise, just retrieve.
				error_log("RETR?");
				return \Stripe\Plan::retrieve($name);
			} catch(\Stripe\Error\InvalidRequest $e) { 
				# Plan doesn't exist, add.
				error_log("INVALID RETRIEVE,  PRAMS=".print_r($details,true));
				if(empty($details))
				{
					return "Could not create plan: No details provided";
				}
				$plan = \Stripe\Plan::create($details);

				return $plan; # Object.
			}
		} catch(Exception $e) { # All wrappers catch generic exceptions
			return "PLAN ERROR: ". $e->getMessage();
		}
	}

	function customer($customer_id,$data=null)
	{
		error_log("CUSTOMER($customer_id)=".print_r($data,true));
		try {
			try {
				if(empty($customer_id))
				{
					throw new \Stripe\Error\InvalidRequest("No customer yet",null);
					# To go below. AND ADD..
				} else {
					$customer = \Stripe\Customer::retrieve($customer_id);
				}
				error_log("RETRIEVED  CUSTOMER=".print_r($customer,true));
				if(!empty($customer->deleted))
				{
					throw new \Stripe\Error\InvalidRequest("Deleted customer",null);
				} else if(!empty($data)) { # Update customer while we're at it.
					try {
						# How do we make sure this is using the same customer key?
						foreach($data as $key=>$val)
						{
							if(!in_array($key, array('plan'))) # Never  specify since always  adds a new subscription
							{ # We always call ->subscription later...
								$customer->$key = $val;
							}
						}
						$customer->save();
					} catch(Exception $e) {
						return "UPDATE CUSTOMER FAIL: ".$e->getMessage();

					}
				}
			} catch(\Stripe\Error\InvalidRequest $e) {
				if(empty($data)) { return null; } # No (valid) customer.

				# NO customer, create.... ONLY IF we're trying to set data AT ALL... reading should just blatantly fail.
				error_log("INVALID REQUEST, data=".print_r($data,true).", CREATING CUSTOMR=".$e->getMessage());
				if(empty($data['source']))
				{
					
					return "No payment information provided";
				}
				$customer = \Stripe\Customer::create($data);
			}
			return $customer;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	# Get or set/update subscription
	function subscription($customer,$subscription_id=null,$details=array()) # just 'plan'=>'plan_id'
	{
		try {
			if(empty($customer)) { 
				return "Customer information not found";
			} else if(is_string($customer)) { # ID
				$customer = $this->customer($customer);
				if(is_string($customer))
				{
					return "CUSTOMER ERROR: $customer"; # Error.
				}
			} 
			try {
				if(empty($subscription_id))
				{
					throw new  \Stripe\Error\InvalidRequest("No subscription yet",null);
				} else { # Existing.
					$subscription = $customer->subscriptions->retrieve($subscription_id);
					# If invalid, throws exception, then creates.
				}
				if($subscription->status != 'active')
				{
					throw new \Stripe\Error\InvalidRequest("Deleted subscription",null);
				} else if(!empty($data)) { # Update subscription while we're at it.
					foreach($data as $key=>$val)
					{
						$subscription->$key = $val;
					}
					$subscription->save();
				}
			} catch(\Stripe\Error\InvalidRequest $e) { # New subscription
				if(empty($details))
				{
					return "Unable to specify subscription details";
				}
				$subscription = $customer->subscriptions->create($details);
				# If subscriptionID isnt set or is invalid, will force creation.
			}
			return $subscription;

		} catch (Exception $e) {
			return "SUBSCRIPTION ERROR: ".$e->getMessage();

		}
	}

	function cancelSubscription($customer,$subscription_id)
	{
		if(is_string($customer))
		{
			$customer = $this->customer($customer);
		}
		if(empty($customer) || is_string($customer))
		{
			return "Cannot cancel subscription: unable to retrieve customer data";
		}

		try {
			$subscription = $customer->subscriptions->retrieve($subscription_id)->cancel();
		} catch(Exception $e) {
			return $e->getMessage();

		}
		return; # Nothing = ok
	}

	function validCreditCard() # ON FILE
	{
		$cust_id = $this->controller->site('stripe_id');

		if(empty($cust_id)) { return false; }
		$customer = $this->customer($cust_id);

		error_log("CHEKING CARD=".print_r($customer,true));

                if(is_string($customer) || empty($customer) || empty($customer['sources']) || empty($customer['sources']['data']) || $customer['sources']['total_count'] < 1)
                { 
                        return false;
                }
		return true;
	}

	function validSubscription($cust_id,$sub_id)
	{
		if(empty($cust_id)) { return false; }
		$customer = $this->customer($cust_id);

		#print_r($customer);
		error_log("CUST_FOUND=".print_r($customer,true));
		#exit(0);

		$subscription = $this->subscription($cust_id,$sub_id);
		return (!empty($subscription) && !is_string($subscription));
	}

	function charge($data) # source (card token) or customer (id) is provided
	{
		try  {
			$charge = \Stripe\Charge::create($data);
		} catch(Exception $e) {
			return $e->getMessage();
		}
		return $charge;
	}

}
