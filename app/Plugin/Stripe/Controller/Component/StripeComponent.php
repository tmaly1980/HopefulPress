<?php
/**
 * StripeComponent
 *
 * A component that handles payment processing using Stripe.
 *
 * PHP version 5
 *
 * @package		StripeComponent
 * @author		Gregory Gaskill <gregory@chronon.com>
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link		https://github.com/chronon/CakePHP-StripeComponent-Plugin
 */

App::uses('Component', 'Controller');

/**
 * StripeComponent
 *
 * @package		StripeComponent
 */
class StripeComponent extends Component {

/**
 * Default Stripe mode to use: Test or Live
 *
 * @var string
 * @access public
 */
	public $mode = 'Test';

/**
 * Default currency to use for the transaction
 *
 * @var string
 * @access public
 */
	public $currency = 'usd';

/**
 * Default mapping of fields to be returned: local_field => stripe_field
 *
 * @var array
 * @access public
 */
 	# THIS VARIES PER OBJECT!
	# We need to just declare everything for any of this to work...
	public $fields = array(
		'customer'=>array(
			'stripe_id' => 'id',
			'default_card'=>true,
			'subscription'=>array(
				'subscription_status'=>'status',
				'plan'=>array(
					'plan_name'=>'name',
					'plan_amount'=>'amount'
				),
			),
			'delinquent'=>true,
			'cards'=>true # Raw object to extract on page....
	 	),
		'plan'=>array(
			'name'=>true,
			'amount'=>true,
			'stripe_id'=>'id',
		),
		'card'=>array(
			'last4'=>true,
			'exp_month'=>true,
			'exp_year'=>true,
			'name'=>true,
			'stripe_id'=>'id',
		),
	);

/**
 * The required Stripe secret API key
 *
 * @var string
 * @access public
 */
	public $key = null;

/**
 * Controller startup. Loads the Stripe API library and sets options from
 * APP/Config/bootstrap.php.
 *
 * @param Controller $controller Instantiating controller
 * @return void
 * @throws CakeException
 * @throws CakeException
 */
	public function startup(Controller $controller) {
		$this->Controller = $controller;

		// load the stripe vendor class IF it hasn't been autoloaded (composer)
		App::import('Vendor', 'Stripe.Stripe', array(
			'file' => 'Stripe' . DS . 'lib' . DS . 'Stripe.php')
		);

		if (!class_exists('Stripe')) {
			throw new CakeException('Stripe API Library is missing or could not be loaded.');
		}

		// if mode is set in bootstrap.php, use it. otherwise, Test.
		$mode = Configure::read('Stripe.mode');
		if ($mode) {
			$this->mode = $mode;
		}

		// set the Stripe API key
		$this->key = Configure::read('Stripe.' . $this->mode . 'Secret');
		if (!$this->key) {
			throw new CakeException('Stripe API key is not set.');
		}

		// if currency is set in bootstrap.php, use it. otherwise, usd.
		$currency = Configure::read('Stripe.currency');
		if ($currency) {
			$this->currency = $currency;
		}

		// field map for charge response, or use default (set above)
		$fields = Configure::read('Stripe.fields');
		if ($fields) {
			$this->fields = $fields;
		}
	}

/**
 * The charge method prepares data for Stripe_Charge::create and attempts a
 * transaction.
 *
 * @param array	$data Must contain 'amount' and 'stripeToken'.
 * @return array $charge if success, string $error if failure.
 * @throws CakeException
 * @throws CakeException
 */
	public function charge($data) { 

		// $data MUST contain 'stripeToken' or 'stripeCustomer' (id) to make a charge.
		if (!isset($data['stripeToken']) && !isset($data['stripeCustomer'])) {
			throw new CakeException('The required stripeToken or stripeCustomer fields are missing.');
		}

		// if amount is missing or not numeric, abort.
		if (!isset($data['amount']) || !is_numeric($data['amount'])) {
			throw new CakeException('Amount is required and must be numeric.');
		}

		// set the (optional) description field to null if not set in $data
		if (!isset($data['description'])) {
			$data['description'] = null;
		}

		// set the (optional) capture field to null if not set in $data
		if (!isset($data['capture'])) {
			$data['capture'] = null;
		}

		// format the amount, in cents.
		$data['amount'] = $data['amount'] * 100;

		Stripe::setApiKey($this->key);
		$error = null;

		$chargeData = array(
			'amount' => $data['amount'],
			'currency' => $this->currency,
			'description' => $data['description'],
			'capture' => $data['capture'],
			'destination' => !empty($data['destination']) ? $data['destination'] : null, # Target account
		);

		if (isset($data['stripeToken'])) {
			$chargeData['source'] = $data['stripeToken'];
		} else {
			$chargeData['customer'] = $data['stripeCustomer'];
		}

		$charge = $this->execute("Charge", "create", $chargeData); # $params may include stripe account it goes into....(ie donations)

		error_log("STRIPE CHARGE=".print_r($charge,true));

		# id doesnt exist..
		#CakeLog::info('Stripe: charge id ' . $charge['id'], 'stripe');

		return $charge;
	}

 	public function execute($class, $method, $data = null, $raw = false)
	{
		#error_log("EXECUTING $method, DATA=".print_r($data,true));
		Stripe::setApiKey($this->key);
		$error = null;

		$result = null;

		try { # Can pass object, ie $customer OR class name.... Stripe_ prefix not required, automatically added...
			$result = call_user_func( is_object($class) ? array($class, $method) : array("Stripe_$class", $method), $data );

			#error_log("RES=".print_r($result,true));;

		} catch(Stripe_CardError $e) {
			$body = $e->getJsonBody();
			$err = $body['error'];
			CakeLog::error(
				'Customer::Stripe_CardError: ' . $err['type'] . ': ' . $err['code'] . ': ' . $err['message'],
				'stripe'
			);
			$error = $err['message'];

		} catch (Stripe_InvalidRequestError $e) {
			$body = $e->getJsonBody();
			$err = $body['error'];
			CakeLog::error(
				'Customer::Stripe_InvalidRequestError: ' . $err['type'] . ': ' . $err['message'],
				'stripe'
			);
			$error = $err['message'];

		} catch (Stripe_AuthenticationError $e) {
			CakeLog::error('Customer::Stripe_AuthenticationError: API key rejected!', 'stripe');
			$error = 'Payment processor API key error.';

		} catch (Stripe_ApiConnectionError $e) {
			CakeLog::error('Customer::Stripe_ApiConnectionError: Stripe could not be reached.', 'stripe');
			$error = 'Network communication with payment processor failed, try again later';

		} catch (Stripe_Error $e) {
			CakeLog::error('Customer::Stripe_Error: Stripe could be down.', 'stripe');
			$error = 'Payment processor error, try again later.';

		} catch (Exception $e) {
			CakeLog::error('Customer::Exception: Unknown error.', 'stripe');
			$error = 'There was an error, try again later.';
		}

		if ($error !== null) {
			// an error is always a string
			return (string)$error;
		}

		if($raw) { return $result; }

		if(is_array($result)) # Done on each item in list.
		{
			$results = array();
			foreach($result as $res)
			{
				$results[] = $this->_formatResult($res);
			}
			return $results;
		} else if (is_object($result) && $result['object'] == 'list') {
			$results = array();
			for($i = 0; $i < $result['count']; $i++)
			{
				$results[] = $this->_formatResult($result['data'][$i]);
			}
			return $results;
		} else {
			return $this->_formatResult($result);
		}
	}

/**
 * The customerCreate method prepares data for Stripe_Customer::create and attempts to
 * create a customer.
 *
 * @param array	$data The data passed directly to Stripe's API.
 * @return array $customer if success, string $error if failure.
 */

	public function customerCreate($data) {

		$customer = $this->execute("Customer", "create", $data);

		if(is_object($customer))
		{
			CakeLog::info('Customer: customer id ' . $customer->id, 'stripe');
		}

		return $customer;
	}

/**
 * The customerRetrieve method gets a customer object for viewing, updating, or
 * deleting.
 *
 * @param string $id Must be an existing customer id.
 * @return object $customer if success, boolean false if failure or not found.
 */
	public function customerRetrieve($id, $raw = false) {
		if(is_array($id) && !empty($id['_instance'])) { return $id['_instance']; } # Shortcut, by object.

		return $this->execute('Customer','retrieve',array('id'=>$id), $raw);
	}

	function customerUpdate($cust_id, $data)
	{
		$customer = $this->customerRetrieve($cust_id,true);
		if(is_string($customer)) { return $customer; }
		foreach($data as $k=>$v)
		{
			$customer->{$k} = $v;
		}
		$customer->save();
	}

/**
 * Returns an array of fields we want from Stripe's response objects
 *
 *
 * @param object $response A successful response object from this component.
 * @return array The desired fields from the response object as an array.
 */
	protected function _formatResult($response) {
		$type = $response['object'];

		#error_log("RESPONSE=".print_r($response,true));

		$fields = !empty($this->fields[$type]) ? $this->fields[$type] : array();

		if(empty($fields)) { return empty($response) ? $response : get_object_vars($response); } # Basic translation...

		# Copy over.

		$result = array();
		foreach ($fields as $k1=>$v1)
		{
			if (is_array($v1)) {
				#error_log("PARSING $k1");
				foreach ($v1 as $k2 => $v2) {
					#error_log("SUB_PARSING $k2");
					if(is_array($v2))
					{
						# Allow two levels of recursion
						foreach($v2 as $k3=>$v3)
						{
							#error_log("SUB_CHECKING resp -> $k1 ->> $k2 -> $v3");
							if (isset($response->$k1->$k2->$v3)) {
								$result[$k3] = $response->$k1->$k2->$v3;
							}

						}
					} else {
						#error_log("CHECKING FOR resp -> $k1 -> $v2");
						if (isset($response->$k1->$v2)) {
							$result[$k2] = $response->$k1->$v2;
						}
					}
				}
			} else {
				if($v1 === true)
				{
					$result[$k1] = $response->$k1;
				} else if (isset($response->$v1)) {
					$result[$k1] = $response->$v1;
				}
			}
		}
		// if no local fields match, return the default stripe_id
		if (empty($result)) {
			$result['stripe_id'] = $response->id;
		}
		$result['_instance'] = $response;
		#error_log("RESULT=".print_r($result,true));
		return $result;
	}
# CARDS
	public function cardDelete($customer_id, $card_id)
	{
		$customer = $this->customerRetrieve($customer_id,true);
		if(is_string($customer))
		{
			return $customer;
		}
		$card = $this->execute($customer->cards, "retrieve", $card_id, true);
		if(is_string($card))
		{
			return $card;
		}
		return $card->delete();
	}

	public function cardUpdate($customer_id, $card_id, $data)
	{
		$customer = $this->customerRetrieve($customer_id,true);
		if(is_string($customer))
		{
			return $customer;
		}
		$card = $this->execute($customer->cards, "retrieve", $card_id, true);

		if(is_string($card))
		{
			return $card;
		}

		foreach($data as $k=>$v)
		{
			$card->$k = $v;
		}
		$card->save();
	}
	public function cardCreate($customer_id, $data, $default = true)
	{
		$customer = $this->customerRetrieve($customer_id,true);
		if(is_string($customer))
		{
			return $customer;
		}
		$card = $this->execute($customer->cards, "create", array('card'=>$data));

		#error_log("SUBMIT CARD=".print_r($data,true));

		#error_log("CARD GOT=".print_r($card,true));

		if(is_string($card))
		{
			return $card;
		}

		if(!empty($default))
		{
			$default_card = $card['stripe_id'];
         		# Update customer's 'default_card'
			#error_log("CARD SAVING DEFAULT=".print_r($card,true));
                        $response = $this->customerUpdate($customer, array('default_card'=>$default_card));
                        if(is_string($response))
                        {
				return $response;
                        }

			/*
			# Now remove all other cards...
			$cards = $this->execute($customer->cards, 'all');
			foreach($cards as $card)
			{
				if($card['stripe_id'] != $default_card)
				{
					$cardObject = $this->execute($customer->cards, 'retrieve', $card['stripe_id'], true);
					if(is_string($cardObject)) { return $cardObject; }

					$cardObject->delete(); # Remove, didn't match.
				}
			}
			*/
		}
	}

# PLANS
	public function planCreate($data) {
		return $this->execute('Plan','create',$data);
	}

	public function planAll() {
		return $this->execute('Plan','all');
	}

# SUBSCRIPTIONS

	# THIS MAY BE PLAIN WRONG...

	public function updateSubscription($customer_id, $plan_id, $card = null)
	{
		$customer = $this->customerRetrieve($customer_id, true);           
		if(is_string($customer)) { return $customer; } # ERROR.                       
		return $this->execute($customer, "updateSubscription", array('plan'=>$plan_id,'card'=>$card));
	}
	# will this overwrite the previous plan???

	public function cancelSubscription($customer_id)
	{
		$customer = $this->customerRetrieve($customer_id, true);           
		if(!is_array($customer)) { return $customer; } # ERROR.                       
		return $this->execute($customer, "cancelSubscription",array('at_period_end'=>true));
	}

}
