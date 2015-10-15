<? if($this->Html->admin()) { ?>
<? if(!empty($nav['donationsEnabled'])) { /* ?>
<div class='alert alert-info'>
	<? if(!empty($stripeCredentials)) { ?>
		Your website has been properly linked to your Stripe account and you can now accept online donations.
		<?= $this->Html->link("Sign out of Stripe", "/user/stripe/auth/logout"); ?>
	<? } else if (!empty($paypalCredentials)) { ?>
		Your website has been properly linked to your Paypal account and you can now accept online donations.
		<?#= $this->Html->link("Sign out of Paypal", "/user/paypal/auth/logout"); ?>
	<? } else { ?>
	OOPS, we had problems getting your account information.
	<? } ?>
</div>
<? */ } else { ?>
<div class='alert alert-info'>
	Donations to your organization are available via 
	<?= $this->Html->link("Paypal", "http://www.paypal.com/"); ?>.
	<br/>
	<br/>
	<?= $this->Html->add("Add your PayPal email",array('plugin'=>false,'admin'=>1,'controller'=>'rescues','action'=>'edit','#'=>'donations')); ?> 
	to enable donations
</div>
<? } ?>
<? } ?>
