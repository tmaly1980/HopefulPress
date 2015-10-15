<?= $this->element("Donation.../Donations/settings"); ?>

<?
# Stripe uses integers in CENTS
# MAKE CUSTOMIZABLE (once asked)
$amounts = array(
	1=>'$1',
	5=>'$5',
	10=>'$10',
	25=>'$25',
	50=>'$50',
	100=>'$100',
	250=>'$250',
	500=>'$500',
	''=>'Other:'
);
$defaultAmount = 10;
?>
<div>
<? if(!empty($paypalCredentials)) { # Make seperate forms since so different. ?>
	<?= $this->element("Donation.../Donations/donate_paypal",array('amounts'=>$amounts,'defaultAmount'=>$defaultAmount)); ?>
<? } else if (!empty($stripeCredentials)) { ?>
	<?= $this->element("Donation.../Donations/donate_stripe",array('amounts'=>$amounts,'defaultAmount'=>$defaultAmount)); ?>
<? } ?>

</div>
<!-- require "other" if nothing else specified -->
<script>
$('.DonateOther').focus(function() {
	$(this).change();
});
$('.DonateOther').change(function() {
	var amount = $(this).val();
	var form = $(this).closest('form');
	$(form).find(".DonateAmount").val(amount);
	$(form).find(".DonateAmounts input[value='']").attr('checked','checked');
});
$('.DonateAmounts input').click(function() {
	var form = $(this).closest('form');
	if(amount = $(this).val())
	{
		$(form).find('.DonateAmount').val(amount);
		$(form).find('.DonateOther').attr('required',false).closest('div').removeClass('required');
	} else {
		$(form).find('.DonateOther').attr('required','required').closest('div').addClass('required');
		$(form).find('.DonateOther').get(0).focus();
	}
});
</script>
