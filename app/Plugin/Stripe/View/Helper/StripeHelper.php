<?
# Add init() at end of view,
# then set name=>false for card number, exp_month,exp_year
# then pass data-stripe= number, exp_month, exp_year 
App::uses("CoreFormHelper", "Core.Helper"); 
class StripeHelper extends CoreFormHelper
{
	function init($id) # ASSUME the id is the model name too....  we need to qualify stripeToken below...
	{
		ob_start();
		?>
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
		<script type="text/javascript">
			Stripe.setPublishableKey('<?= Configure::read("Stripe.PublishableKey") ?>');
		</script>
		<script type="text/javascript">
		$('#<?=$id ?>').on('success.form.bv', function(event) { // pass to form validator so it doesnt submit twice!
			event.preventDefault();

			var form = $(event.target);
			var validator = form.data('formValidation');

			form.find('button[type=submit]').prop('disabled',true);

			Stripe.card.createToken(form, function(status, response) {
				console.log("TOKEN_CREATE=");
				console.log(response);
				if(response.error)
				{
					//form.find('.payment-errors').text(response.error.message).show();
					BootstrapDialog.alert({title: 'Error processing payment', message: response.error.message, type: BootstrapDialog.TYPE_DANGER});
					form.find('button[type=submit]').prop('disabled',false);
				} else {
					//form.find('.payment-errors').text('').hide();
					var token = response.id;
					form.append($("<input type='hidden' name='data[<?=$id?>][source]'/>").val(token));
					console.log("TOKEN="+token);
					validator.defaultSubmit();  // CONTINUE
				}
			});

			return false;
		});
		</script>
		<?
		return ob_get_clean();
	}

}
