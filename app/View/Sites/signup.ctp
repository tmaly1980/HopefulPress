<? $this->layout = 'Www.plain'; ?>
<? $this->assign("page_class", "center col-md-6"); ?>
<? $this->assign("page_title", "Hopeful Press Website Signup"); ?>
<div class="sites form">
	<?= $this->element("../Sites/form"); ?>

	<!--
	<div class='alert alert-success'>
	You'll be asked for some basic information to create your website next. It'll only take a few minutes.
	</div>
	-->

	<!--<p class='darkgrey'>
	First month free, then just $10/mo. No obligations, cancel anytime.
	</p> -->

</div>
