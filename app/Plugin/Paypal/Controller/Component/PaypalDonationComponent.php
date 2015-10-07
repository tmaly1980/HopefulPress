<?
# Streamlined donation handling

class PaypalDonationComponent extends Component
{
	function startup(Controller $controller)
	{
		$this->controller = $controller;
		return parent::startup($controller);
	}

	function donate($data)
	{
	}

	# Cancel subscription
	function cancelSubscription()
	{
		$customer_id = $this->controller->site('stripe_id'); # Existing...
		$subscription = $this->Stripe->cancelSubscription($customer_id);
		/* If there's a failure then it's not a valid subscriber anyway, so ignore
		if(is_string($subscription))
		{
			return "Could not cancel billing: $subscription";
		}
		*/
		return false;
	}

	# Centralized charging/updating of credit card....
	function saveSubscription($data) # Creates customer profile if needed. And initiates charge if none exists...
	{
		# if form HAS 'stripe_id' (customer info), then we USE that for existing....

		# Create customer profile
		$data['description'] = $this->controller->site("title") . " / " . $this->controller->user("full_name") . " - " . $this->controller->user("email");

		$customer_id = $this->controller->site('stripe_id'); # Existing...
		$status = $this->controller->site('plan'); # Existing...

		if(!empty($status) && empty($data['plan'])) { 
			$data['plan'] = $status;
		} # default
		# What site already has is default, no need to throw away.
		# Not changing, so keep.

		$new = !empty($status);

		$customer = null;

		# Normally we might retrieve an existing profile....
		if(!empty($customer_id))
		{
			$customer = $this->Stripe->customerRetrieve($customer_id);
		} 
		if(empty($customer) || !empty($customer['deleted']))
		{
			error_log("CREATING CUSTOMER=".print_r($data,true));
			$customer = $this->Stripe->customerCreate($data);
			error_log("CUSTOMER SAVED=".print_r($customer,true));
			# While the customer is created, it also adds the card.
		}
		if(is_string($customer)) # FAIL
		{
			return "Unable to process your payment information: $customer";
		} else { # Success.
			if(!$this->controller->Site->saveField('stripe_id',$customer['stripe_id']) ||
				!$this->controller->Site->saveField('upgraded',date('Y-m-d'))
			)
			{
				return "Unable to save your payment information";
			}
			error_log("saving stripe_id=".$customer['stripe_id']);

			# Make sure 'plan' exists...
			# XXX TODO ALTERNATE PLANS!!! 
			#$planName = $this->defaultPlan;
			$planName = !empty($data['plan']) ? $data['plan'] : $this->defaultPlan;

			$plan = $this->plan($planName);

			if(is_string($plan))
			{
				return "Couldn't initialize plan: $plan";
			} else {
				error_log("CUST=".print_r($customer,true));
				error_log("PLAN=".print_r($plan,true));

				# Make sure they have a valid subscription... If they don't, it'll charge them immediately...
				# Include card for add/update
				$subscription = $this->Stripe->updateSubscription($customer, $plan['stripe_id'], !empty($data['card']) ? $data['card'] : null);
				# If already has card on file, should work fine...

				if(is_string($subscription)) { return "Could not process your payment: $subscription"; }

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
		}
		return;
		# Default is ok, no error message.
	}

	function plan($name)
	{
		$details = $this->plans[$name];
		$plan_id = $details['id'];
		error_log("PLAN $name, $plan_id=".print_r($details,true));

		$plans = $this->Stripe->planAll();
		error_log("PLANS=".print_r($plans,true));
		$plan = Hash::extract($plans, "{n}[stripe_id=$plan_id]");
		if(!empty($plan[0])) { $plan = $plan[0]; }

		error_log("PLAN ($plan_id)=".print_r($plan,true));

		if(empty($plan)) # Create...
		{
			$plan = $this->Stripe->planCreate($details);
		}
		return $plan;
	}

	function validCreditCard()
	{
		$cust_id = $this->controller->site('stripe_id');
		error_log("CUST_ID=$cust_id");
		if(empty($cust_id)) { return false; }
		$customer = $this->Stripe->customerRetrieve($cust_id);

                if(empty($customer['cards']) || empty($customer['cards']['data']) || $customer['cards']['total_count'] < 1)
                { 
                        return false;
                }
		return true;
	}

	function validSubscription()
	{
		$cust_id = $this->controller->site('stripe_id');
		error_log("CUST_ID=$cust_id");
		if(empty($cust_id)) { return false; }
		$customer = $this->Stripe->customerRetrieve($cust_id);

		#print_r($customer);
		error_log("CUST_FOUND=".print_r($customer,true));
		#exit(0);

		if(empty($customer['subscription_status']) || $customer['subscription_status'] != 'active')
		{
			return false;;
		}
		return true;
	}

	function getSubscription() # Listing 'billing' details.
	{
		if($cust_id = $this->controller->site('stripe_id'))
		{
			$customer = $this->Stripe->customerRetrieve($cust_id);
			# Get customer, plan, and subscription.
			# Sift through billing card, save as 'card'
			if(!empty($customer['default_card']) && !empty($customer['cards']['data']))
			{
				$card_id = $customer['default_card'];
				foreach($customer['cards']['data'] as $card)
				{
					if($card->id == $card_id)
					{
						$customer['card'] = $card;
					}
				}
			}
			return $customer;
		}
		return null;
	}

	function beforeFilter()
	{
		# Get active plan, report to vars...
		#$subscription = $this->getSubscription();
		#Configure::write("site_subscription", $subscription);
		# TOO INTENSE OF A QUERY FOR EVERY PAGE LOAD...
	}



}
