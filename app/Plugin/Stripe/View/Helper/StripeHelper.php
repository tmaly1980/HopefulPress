<?
App::uses("CoreFormHelper", "Core.Helper"); 
class StripeHelper extends CoreFormHelper
{
	function init($id)
	{
		ob_start();
		?>
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
		<script type="text/javascript">
			Stripe.setPublishableKey('<?= Configure::read("Stripe.PublishableKey") ?>');
			//$('#<?=$formId?>').prepend("<div class='payment-errors alert alert-warning marginbottom25' style='display:none;'></div>");
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
					form.append($("<input type='hidden' name='data[<?= $this->defaultModel ?>][stripeToken]'/>").val(token));
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
