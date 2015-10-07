<? $this->assign("page_title", "Pricing &amp; Signup"); ?>

<div class='row col-md-9 center'>
	<? 
	Configure::load("Stripe.billing");
	$plans = Configure::read("Billing.plans");
	# Give them an opportunity to decide on a paid plan before they try...
	# OR give them an option with a "Free Trial" button to not fuss with costs yet.
	# XXX We will need to have a 'trial' flag for the site,  which will trigger the trial warning if need be.
	?>

	<div class='alert alert-success'>
	All of our website plans include a <b>30-DAY FREE TRIAL</b> and a <b>http://YourRescue.hopefulpress.com</b> address. No credit card required, no setup fees and no long-term contracts/obligations. Cancel any time.
	</div>
	<?= $this->element("Stripe.plans",array('plans'=>$plans,'signup'=>true)); ?>

	<hr/>

	<div class='alert alert-warning'>
		If you think you'll need special one-on-one help for your custom project, fill out our consultation form, and we'll get back to you with a quote.
		<br/>
		<br/>
		<div class='center_align'>
			<?= $this->Html->link("Request a Website Consultation", "/consult", array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

</div>
