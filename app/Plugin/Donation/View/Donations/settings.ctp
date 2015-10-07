<? if($this->Html->can_edit()) { ?>
<div class='alert alert-info'>
<? if(!empty($nav['donationsEnabled'])) { ?>
	<? if(!empty($stripeCredentials)) { ?>
		Your website has been properly linked to your Stripe account and you can now accept online donations.
		<?= $this->Html->link("Sign out of Stripe", "/user/stripe/auth/logout"); ?>
	<? } else if (!empty($paypalCredentials)) { ?>
		Your website has been properly linked to your Paypal account and you can now accept online donations.
		<?= $this->Html->link("Sign out of Paypal", "/user/paypal/auth/logout"); ?>
	<? } else { ?>
	OOPS, we had problems getting your account information.
	<? } ?>
<? } else { ?>
	Donations to your organization are available via 
	<?= $this->Html->link("Stripe", "http://www.stripe.com/"); ?> or
	<?= $this->Html->link("Paypal", "http://www.paypal.com/"); ?>.
	<p> Processing fees for each transaction are <b>2.9% + $0.30</b>. <!--Stripe has no setup fees. Paypal one-time setup is $29.-->
	<p>To enable donations, simply sign in or create an account with either service below:

	<br/>
	<br/>

	<div align='center'>
			<?= $this->Html->link("Connect with Stripe", "/user/stripe/auth/login", array('class'=>'white btn btn-success')); ?>
			&nbsp;
			&nbsp;
			&nbsp;
			<?= $this->Html->link("Connect with Paypal", "/user/paypal/auth/edit", array('class'=>'white btn btn-success')); ?>
	</div>
<? } ?>
</div>
<? } ?>
