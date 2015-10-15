<?
App::uses("Paypal", "Paypal.Lib");

class DonationsController extends AppController
{
	var $components = array();#'Stripe.Stripe');
	var $uses = array('Donation.Donation','Donation.DonationPage');#,'Paypal.PaypalCredential','Stripe.StripeCredential','Donation.DonationPage','Stripe.StripeCredential');
	var $helpers = array('Stripe.Stripe','Paypal.Paypal');

	var $rescue_required = true;

	function ipn() # Paypal response.
	{
		error_log("IPN =".print_r($this->request->data,true));
		if($this->_ipn_validated()) # Check for validity from paypal...
		{
			$action = $this->request->data['txn_type'];
			error_log("ACTION=$action");
			# COULD BE COUNTLESS VARIANTS, only worry about new s
			# subscription vs recurring payment?

			# SAVING XXX
			if(in_array($action, array('web_accept','recurring_payment_profile_created','subscr_payment')) && $this->request->data['payment_status'] == 'Completed')
			{
				$recurring = in_array($this->request->data['txn_type'], array('subscr_payment','recurring_payment_profile_created'));
				$chargeKey = ($recurring?"subscr_id":"txn_id");
				$donation = array( # CONVERT
					'adoptable_id'=>!empty($this->request->data['custom']) ? $this->request->data['custom'] : null,
					'name'=>join(" ", array($this->request->data['first_name'], $this->request->data['last_name'])),
					'email'=>!empty($this->request->data['payer_email']) ? $this->request->data['payer_email'] : null,
					'amount'=>!empty($this->request->data['mc_gross']) ? $this->request->data['mc_gross'] : null,
					'note'=>!empty($this->request->data['memo']) ? $this->request->data['memo'] : null,
					'recurring'=>$recurring,
					'charge_id'=>!empty($this->request->data[$chargeKey]) ? $this->request->data[$chargeKey] : null,
					# XXX TODO confirm fields/values are proper.
				);
				error_log("OK, SAVING=".print_r($donation,true));
				# save 'donation' record.
				if($this->Donation->save($donation))
				{
					$donation = $this->Donation->read();
					$this->sendDonationEmail($donation);
				}
			} else if(in_array($action, array('subscr_cancel'))) { 
			# CANCELING/MODIFYING 
				$donation = $this->Donation->first(array('charge_id'=>$this->request->data['subscr_id']));
				if(!empty($donation))
				{
					$donation['Donation']['recurring_cancelled'] = date('Y-m-d H:i:s');
					$this->Donation->save($donation);
					error_log("SUCCESSFULLY CANCELED SUBSCRIPTION FOR={$this->request->data['subscr_id']}");
					# DONT bother with email notification....
				}
			}

		}
		exit(0); # Empty response.
	}
	function _ipn_validated()
	{
		if(!empty($this->request->data))
		{
			$data = $this->request->data;
			$error = null;

			# Make sure it's a complete transaction, first...
			#if(empty($data['payment_status']) || $data['payment_status'] != 'Completed')
			#{
				$error = "payment_status != Completed";
			# NO sense in checking receiver_email or mc_gross (price)
			#} else { # OK
				# Now make sure it's legit.
				$url = "https://www.".(!empty($this->request->data['test_ipn'])?"sandbox.":"")."paypal.com/cgi-bin/webscr";
				$data['cmd'] = '_notify-validate';
				$response = $this->http_post($url, $data);
				if(preg_match("/VERIFIED/", $response)) { return true; }
				else { $error = "IPN_INVALID_RESPONSE: $response";}
			#}

			if(!empty($error))
			{
				error_log("IPN FAILED: $error; DATA=".print_r($data,true)); 
			}
		}
		return false;
	}

	function thanks()
	{
		return $this->setSuccess("Thank you for your contribution!", array('controller'=>'donation_pages','action'=>'view'));
	}
	

	function user_index()
	{
		$this->set("donations", $this->paginate());
	}

	function index()
	{
		return $this->redirect(array('controller'=>'donation_pages','action'=>'view'));
	}

	function donate($adoptable_id=null) # Process
	{
		# XXX MAKE SURE THEY ARE ENABLED/SIGNED IN FOR DONATIONS
		$creds = $this->_credentials();

		if(empty($creds))
		{
			return; # DONT PROCESS
		}

		if(!empty($this->request->data['Donation']))
		{
			error_log("DONATION DETAILS, SHOULD INCLUDE stripeToken....=".print_r($this->request->data['Donation'],true));
			$data = $this->request->data['Donation'];
			$amount = $data['amount'];
			$email = $data['email'];
			if(!empty($data['subscribe']))
			{
				# XXX  TODO add email to subscribers
			}
			# XXX need to specify SITE'S Stripe account, provide THEIR org info , etc...

			#$params = array('stripe_account'=>$creds['DonationCredential']['stripe_user_id']);
			#$params = $creds['DonationCredential'];#array('stripe_account'=>$creds['DonationCredential']['stripe_user_id']);

			if(!empty($data['recurring'])) # Initialize subscription...
			{
				$data['description'] = "Monthly donation to ".$this->Multisite->get("title");

				if($string = $this->_chargeRecurring($data, $creds, $email))

				{
					return $this->setError("Could not initialize recurring charge: $customer",  "/donation");
				}
			} else { # One time donation.
				$data['description'] = "One-time donation to ".$this->Multisite->get("title");

				# XXX FIX ONE TIME CHARGE

				if(is_string($result = $this->_charge($data,$creds)))
				{
					return $this->setError("Could not charge payment: $result", "/donation");
				}
			}

			# Should we be saving any transaction refids?????
			if(!empty($result['id'])) # id => charge ref id
			{
				$this->request->data['Donation']['charge_id'] = $result['id'];
			}

			if($this->Donation->save($this->request->data))
			{
				$donation = $this->Donation->read();
				$this->sendDonationEmail($donation);
			}

			$this->setSuccess("We greatly appreciate your contribution!", array('action'=>'index'));
		}
		$this->set("adoptable_id",$adoptable_id);
	}

	function _chargeRecurring($data,$creds,$email)
	{
		if(!empty($creds['PaypalCredential']))
		{
			return $this->_charge_paypal($data);
			return $this->_chargeRecurring_paypal($data,$creds,$email);
		} else if(!empty($creds['StripeCredential'])) {
			return $this->_chargeRecurring_stripe($data,$creds,$email);
		} else {
			return "Cannot process recurring charge: No payment system configured";
		}
	}

	function  _chargeRecurring_paypal($data,$creds,$email)
	{
		if(empty($data['credit_card_id']))
		{
			return "Could not charge: Credit card ID payment information not available";
		}
		try
		{
			# Create/find plan.
			$plan_id = null;
			$plans = $this->Paypal->planAll(); # TODO, remove 'plans' key
			$plan = Hash::extract($plans, "{n}.payment_definitions.amount[value=$amount]");

			if(empty($plan))
			{
				$planDetails = array(
					'name'=>"Monthly Recurring Donation - \$$amount",
					'description'=>"Monthly Recurring Donation",
					'type'=>"INFINITE",
					'payment_definitions'=>array(
						array(
							'name'=>"Monthly Recurring Donation",
							'type'=>'REGULAR',
							'amount'=>array(
								'currency'=>'USD',
								'value'=>$amount,
							),
							'frequency'=>"MONTH",
							'frequency_interval'=>1,
							'cycles'=>0,
						)
					)
				);
				if(is_string($plan = $this->Paypal->planCreate($planDetails))) 
				{
					return $this->setError("Could not create recurring charge plan: $plan", "/donation");
				}
			}
			$plan_id = $plan['id'];

			# Make sure plan is ACTIVE
			$activePlan = array(
				'op'=>"replace",
				'path'=>"/",
				'value'=>array(
					'state'=>"ACTIVE"
				)
			);
			$this->Paypal->updatePlan($plan_id, $activePlan);

			# Create billing agreement
			# XXX TODO
			$agreement = array(
				'name'=>'Recurring Monthly Donation',
				'description'=>'Recurring monthly donation for '.$this->Multisite->get("title"),
				'start_date'=>date('c'),
				'plan'=>array( 'id'=>$plan_id ),
				'payer'=>array(  
					'payment_method'=>'credit_card',
					'funding_instruments'=>array(
						0=>array(
							'credit_card_token'=>$data, # credit_card_id, # BUT NOT payer_id
						),
					)
				),


			);
			return $this->Paypal->createBillingAgreement($payment);
		} catch(Exception $e) {
			return $e->getMessage();
		}
	}

	function _chargeRecurring_stripe($data,$creds,$email)
	{
		$amount = $data['amount'];
		$amount_cents = intval($amount*100); # Cents.

		# Create/find plan.
		$planId = "{$amount}monthly";
		$planDetails = array(
			'id'=>$planId,
			'amount'=>$amount_cents,
			'currency'=>'usd',
			'name'=>"Monthly Recurring Donation - \$$amount",
			'interval'=>"month",
		);
		$plan  = $this->StripeBilling->plan($planDetails); # Might get existing if there.

		if(is_string($plan = $this->Stripe->planCreate($planDetails)))
		{
			return $this->setError("Could not create recurring charge plan: $plan", "/donation");
		}
		$plan_id = $plan['id'];

		$params = array(
			'source'=>$creds['StripeCredential']['stripeToken'], # Credit card saved from Stripe.js
			'plan'=>$plan_id,
			'email'=>$email
		);

		return $this->StripeBilling->customer($params);
	}

	function _credentials() 
	{
		if($stripe = $this->_credentials_stripe())
		{
			$this->set("stripeCredentials", $stripe); 
			# Set API key
			\Stripe\Stripe::setApiKey($stripe['StripeCredential']['access_token']); # Simpler than using public key (don't seem to have)
			return $stripe;
		} else if($paypal = $this->_credentials_paypal()) {
			#
			$paypal['sandboxMode'] = Configure::read("prod") ? false : true;
			#$this->Paypal = new Paypal($paypal);
			$this->set("paypalCredentials", $paypal); # 
			return $paypal;
		}
	}
	function _credentials_stripe()
	{
		if(empty($this->StripeCredential)) { return null; }
		return $this->StripeCredential->count() ? $this->StripeCredential->first() : null;
	}

	function _credentials_paypal()
	{
		#  HACKED
		if(!empty($this->rescue('paypal_email')))
		{
			$rescue = $this->rescue();
			$rescue['PaypalCredential'] = $rescue['Rescue'];
			return $rescue;
		}
		if(empty($this->PaypalCredential)) { return null; }
		#$pp = Configure::read("Paypal"); # FOR NOW...
		#return $pp;
		return $this->PaypalCredential->count() ? $this->PaypalCredential->first() : null;
	}
		

	function _charge($data,$creds)
	{
		if(!empty($creds['PaypalCredential']))
		{
			return $this->_charge_paypal($data);
		} else if(!empty($creds['StripeCredential'])) {
			return $this->_charge_stripe($data);
		} else {
			return "Cannot process charge: No payment system configured";
		}
	}

	function _charge_stripe($data)
	{
		# only takes one parameter...
		return $this->StripeBilling->charge($data);
	}

	function  _charge_paypal($data,$params) # Donation details, payment token info....
	{
		if(empty($data['credit_card_id']))
		{
			return "Could not charge: Credit card ID payment information not available";
		}
		try
		{
			$payment = array(
				'intent'=>'sale',
				'payer'=>array(
					'payment_method'=>'credit_card',
					'funding_instruments'=>array(
						0=>array(
							'credit_card_token'=>$data, # credit_card_id, # BUT NOT payer_id
						),
					)
				),
				'transactions'=>array(
					0=>array(
						'amount'=>array(
							'total'=>$data['amount'],
							'currency'=>'USD'
						),
						'description'=>$data['description'],
					)
				)
			);
			$this->Paypal->chargeStoredCard($payment);
		} catch(Exception $e) {
			return $e->getMessage();
		}
	}
}
