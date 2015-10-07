<?
# FORGET THIS...
App::uses("CoreFormHelper", "Core.Helper");
class PaypalHelper extends CoreFormHelper
{
	# If on rescue.hopefulpress.com, work in SANDBOX mode....
	function create($model=null,$options=array(),$attrs=array())
	{
		$prod = (Configure::read("prod") && (Configure::read("hostname") != 'rescue'));
		$server = $prod ? "www.paypal.com" : "www.sandbox.paypal.com";

		ob_start();
		echo parent::create(false,array('method'=>'post','url'=>"https://$server/cgi-bin/webscr",'validate'=>false),$attrs);
		?>
		<input type='hidden' name='cmd' value='<?= !empty($options['recurring'])?"_xclick-subscriptions":"_donations"?>'/>
		<input type='hidden' name='no_shipping' value='1'/>
		<input type='hidden' name='return' value='<?= $this->Html->url("/donation/thanks",true); ?>'/>
		<input type='hidden' name='notify_url' value='<?= $this->Html->url("/donation/ipn",true); ?>'/>

		<?
		return ob_get_clean();
	}

	function input($field,$opts=array(),$attrs=array())
	{
		if(method_exists($this,$field)) { return $this->$field($opts,$attrs); }
	}

	function name()
	{
	        return $this->input(false, array('div'=>'col-md-6','label'=>'First Name','name'=>'first_name')).
	        	$this->input(false, array('div'=>'col-md-6','label'=>'Last Name','name'=>'last_name'));
		# XXX so then in ipn, we need to merge....
	}

	function note()
	{
		return $this->input("note", array('name'=>'custom','label'=>'Note (optional)','type'=>'text')); 
	}

	function email()
	{
	 	return $this->input("email", array('name'=>'email','div'=>'col-md-6','label'=>'Your Email','required'=>1)); 

	}

	function amount($opts=array())
	{
		if(!isset($opts['name'])) { $opts['name'] ='amount'; } # Might override, ie subscriptions
		$opts['class'] = 'DonateAmount';
	 	return $this->hidden("amount", $opts);
	}


	function adoptable($opts=array())
	{
		$opts['name'] = 'item_number';
		return $this->hidden(false,$opts);
	}

	function end($options=null,$secureAttrs=array())
	{
		ob_start();
		echo parent::end($options,$secureAttrs);
		?>
		<!--<script type="text/javascript" src="/paypal/js/paypal.js"></script>-->
		<script type="text/javascript">
		/*
		$('#<?=$this->formID ?>').on('success.form.bv', function(event) { // pass to form validator so it doesnt submit twice!
			event.preventDefault();

			var form = $(event.target);
			var validator = form.data('formValidation');

			form.find('button[type=submit]').prop('disabled',true);

			// we need to pass client_id/secret to get token info, but this is exposed to browser!!!
			// maybe we need to query our OWN servers?

			Paypal.card.createToken(form, function(status, response) {
				console.log("TOKEN_CREATE=");
				console.log(response);
				if(status >= 400) 
				{
					//form.find('.payment-errors').text(response.error.message).show();
					BootstrapDialog.alert({title: 'Error processing payment', message: response.message, type: BootstrapDialog.TYPE_DANGER});
					form.find('button[type=submit]').prop('disabled',false);
				} else {
					//form.find('.payment-errors').text('').hide();
					var token = response.id;
					form.append($("<input type='hidden' name='data[<?= $this->defaultModel ?>][credit_card_id]'/>").val(token));
					console.log("TOKEN="+token);
					validator.defaultSubmit();  // CONTINUE
				}
			});

			return false;
		});
		*/
		</script>
		<?
		return ob_get_clean();
	}

	# These fields are named after whatever Paypal needs...
	# XXX TODO
	function card_number()
	{
		return 
			$this->input(false, array('name'=>'type','options'=>array('visa'=>'Visa','mastercard'=>'Master Card','discover'=>'Discover','amex'=>'American Express','label'=>'Card Type','data-paypal'=>'type'))).
			$this->input(false, array('name'=>'number','label'=>'Credit Card Number','type'=>'text', 'size'=>20, 'data-paypal'=>'number'));
	}

	function card_name()
	{
		return 
			$this->input(false, array('name'=>'first_name','label'=>'First Name on Card','type'=>'text', 'size'=>16, 'data-paypal'=>'first_name')).
			$this->input(false, array('name'=>'last_name','label'=>'Last Name on Card','type'=>'text', 'size'=>16, 'data-paypal'=>'last_name'));
	}

	function expiration($m,$y,$mo=array(),$yo=array())
	{
		return parent::expiration(false,false, array('data-paypal'=>"expire_month",'name'=>'expire_month'),array('data-paypal'=>"expire_year",'name'=>'expire_year')); 
	}
}
