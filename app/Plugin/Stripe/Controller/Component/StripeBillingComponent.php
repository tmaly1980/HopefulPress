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

	# Create hosting subscription.
	function hostingSubscription($data=null) # Plan is passed...
	{
		# Maybe someday simplify...
		$custId = $this->controller->site('stripe_id'); # Existing...
		$subId = $this->controller->site('subscription_id'); # Existing...
		if(!empty($data))
		{
			$data['description'] = "Website Hosting - Hopeful Press (".$this->controller->site("title") . "; ".$this->controller->site("hostname").".hopefulpress.com)";

			$plan = $this->plan($data['plan']); # Make sure plan exists.
			if(is_string($plan))
			{
				return $plan;
			}
		}

		$customer = $this->customer($custId,$data); # Create if needed.
		if(is_string($customer))
		{
			return $customer;
		}

		return $this->subscription($customer,$subId,$data);
	}

	/* OBSOLETE/REDUNDANT
	# Centralized charging/updating of credit card....
	function saveSubscription($data,$customer_id=null,$subscription_id=null) # Creates customer profile if needed. And initiates charge if none exists...
	{
		$status = $this->controller->site('plan'); # Existing...

		if(!empty($status) && empty($data['plan'])) { 
			$data['plan'] = $status;
		} # default
		# What site already has is default, no need to throw away.
		# Not changing, so keep.

		# Might be passed string OR object (ie domain_registration details)

		# PLAN NEEDS TO EXIST BEFORE CUSTOMER
		$planNames = array_keys($this->config['plans']);
		$planName = !empty($data['plan']) ? $data['plan'] : $planNames[0];
		$plan = $this->plan($planName);
		if(is_string($plan))
		{
			return $plan; # Error
		}

		$new = !empty($status);

		$customer = $this->customer($customer_id,$data);

		if(is_string($customer)) # FAIL
		{
			return "Unable to process your payment information: $customer";
		} else { # Success.
			if(!$this->controller->Site->saveField('stripe_id',$customer->id))
			{
				return "Unable to save your payment information";
			}

			error_log("CUST=".print_r($customer,true));
			error_log("PLAN=".print_r($plan,true));

			# Make sure they have a valid subscription... If they don't, it'll charge them immediately...

			# XXX pass payment information in case account already existed but without card.
				$subDetails = array('plan'=>$plan['id']);
				if(empty($customer['sources'])) {  # NO payment info
					if(empty($data['source'])) # None just given
					{
						return "No payment information provided";
					}
					$subDetails['source'] = $data['source']; # Pass.
				}
				$subscription = $this->subscription($customer,$subscription_id,$subDetails);
				if(is_string($subscription)) { return "Could not process your payment: $subscription"; }

				if(!$this->controller->Site->saveField('subscription_id',$subscription->id))
				{
					return "Unable to update your account subscription";
				}

				# NOTIFY MANAGER OF UPGRADE (if new)
				if($new)
				{
					$this->controller->managerEmail("Subscription upgrade", "sites/manager_upgrade");
				}

				# Also save site account status
				if(!$this->controller->Site->saveField('plan',$planName))
				{
					return "Unable to upgrade your account";
				}
		}
		return;
		# Default is ok, no error message.
	}
	*/

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
				return \Stripe\Plan::retrieve($name);
			} catch(\Stripe\Error\InvalidRequest $e) { 
				# Plan doesn't exist, add.
				if(empty($details))
				{
					return "Could not create plan: No details provided";
				}
				$plan = \Stripe\Plan::create($params);

				return $plan; # Object.
			}
		} catch(Exception $e) { # All wrappers catch generic exceptions
			return $e->getMessage();
		}
	}

	function customer($customer_id,$data=null)
	{
		try {
			try {
				if(empty($customer_id))
				{
					throw \Stripe\Error\InvalidRequest("No customer yet");
					# To go below.
				} else {
					$customer = \Stripe\Customer::retrieve($customer_id);
				}
				if(!empty($customer->deleted))
				{
					throw \Stripe\Error\InvalidRequest("Deleted customer");
				} else { # Update customer while we're at it.
					foreach($data as $key=>$val)
					{
						$customer->$key = $val;
					}
					$customer->save();
				}
			} catch(\Stripe\Error\InvalidRequest $e) {
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
	function subscription($customer,$subscription_id=null,$details=array())
	{
		try {
			if(empty($customer)) { 
				return "Customer information not found";
			} else if(is_string($customer)) { # ID
				$customer = $this->customer($customer);
				if(is_string($customer))
				{
					return  $customer; # Error.
				}
			} 
			try {
				if(empty($subscription_id))
				{
					throw \Stripe\Error\InvalidRequest("No subscription yet");
				} else { # Existing.
					$subscription = $customer->subscriptions->retrieve($subscription_id);
					# If invalid, throws exception, then creates.
				}
				if($subscription->status != 'active')
				{
					throw \Stripe\Error\InvalidRequest("Deleted subscription");
				} else { # Update subscription while we're at it.
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
			return $e->getMessage();

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
