<? #$this->layout = 'Www.plain'; ?>
<? $this->assign("page_class", "center col-md-6"); ?>
<? $this->assign("page_title", "Create your rescue website instantly"); ?>
<div class="sites form lightgreybg">
	<?= $this->element("../Rescues/form"); ?>

	<!--
	<div class='alert alert-success'>
	You'll be asked for some basic information to create your website next. It'll only take a few minutes.
	</div>
	-->

	<!--<p class='darkgrey'>
	First month free, then just $10/mo. No obligations, cancel anytime.
	</p> -->

</div>
