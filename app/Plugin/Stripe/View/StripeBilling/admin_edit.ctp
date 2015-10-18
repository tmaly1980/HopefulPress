<? $stripe_id = $rescue['Rescue']['stripe_id']; ?>
<? $mode = Configure::read("Stripe.mode"); ?>
<? $this->assign("page_title", (!empty($stripe_id) ? "Update Payment Details" : "Add Payment Details ").($mode == 'Test' ? ' - TEST':'')); ?>
	<div class='alert alert-info'>
		Please provide your payment information for billing purposes. You can cancel your website service at any time. Note: Your credit card information is stored securely via our payment processing system.
	</div>
<div class="siteDesign form border lightgreybg">

<?= $this->Form->create('StripeBilling', array('id'=>'StripeBilling','url'=>$this->here)); ?>
	<?= !empty($goto) ? $this->Form->hidden("goto", array('value'=>$goto)) : null; ?>
<div class='inline_label label175'>
	<?= $this->Form->hidden("form_submit",array('name'=>'data[form_submit]','id'=>'form_submit')); ?>

	<div>
	<b><?= $plans[$plan]['metadata']['title'] ?></b>: <?= sprintf("$%u per %s", $plans[$plan]['amount']/100, $plans[$plan]['interval']); ?>
	</div>
	
	<hr/>

	<?####= $this->Form->hidden("StripeBilling.source.object", array('value'=>'card')); # REQD ?>

	<?= $this->Form->input(false, array('name'=>false,'data-stripe'=>'number','label'=>'Credit Card Number','required'=>1,'length'=>16,'class'=>'width225','maxlength'=>16,'counter'=>false,'filter'=>"/[0-9]+/")); ?>
	<?= $this->Form->expiration('StripeBilling.source.exp_month','StripeBilling.source.exp_year',array('name'=>false,'data-stripe'=>'exp_month'),array('name'=>false,'data-stripe'=>'exp_year')); ?>

	<?= $this->Form->input(false, array('name'=>false,'data-stripe'=>'name','label'=>'Cardholder Name','class'=>'width225')); ?>

	<? # XXX TODO allow for selection, and then change site view page to have 'upgrade/downgrade' option.... ?>

	<?= $this->Form->hidden("StripeBilling.plan",array('value'=>$plan)); ?>


	<? if(!empty($setup)) { ?>
		<?= $this->Form->submit("Save Payment Information",array('div'=>'input center_text')); ?>
	<? } else { ?>
		<?= $this->Form->save("Save Payment Information",array('cancel'=>array('action'=>'view'))); ?>
	<? } ?>
</div>


	<?= $this->Form->end(); ?>
</div>
<?= $this->Stripe->init('StripeBilling'); ?>
