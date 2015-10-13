<? $stripe_id = $this->Rescue->get("stripe_id"); ?>
<? $mode = Configure::read("Stripe.mode"); ?>

<div class="form border lightgreybg">
<h3><?= !empty($stripe_id) ? "Update Payment Details": "Add Payment Details" ?><?= ($mode=='Test'?' - TEST':''); ?></h3>

<?= $this->Form->create('StripeBilling', array('url'=>$this->here,'data-update'=>'PaymentDetails')); ?>
	<?= !empty($goto) ? $this->Form->hidden("goto", array('value'=>$goto)) : null; ?>
<div class='inline_label label175'>
	<?= $this->Form->hidden("form_submit",array('name'=>'data[form_submit]','id'=>'form_submit')); ?>

	<div>
	<b><?= $plans[$plan]['metadata']['title'] ?></b>: <?= sprintf("$%u per month", $plans[$plan]['amount']/100); ?>
	</div>
	
	<hr/>

	<?= $this->Form->hidden("StripeBilling.source.object", array('value'=>'card')); # REQD ?>
	<?= $this->Form->input("StripeBilling.source.number", array('label'=>'Credit Card Number','required'=>1,'length'=>16,'class'=>'width225','maxlength'=>16,'counter'=>false,'filter'=>"/[0-9]+/")); ?>
	<?= $this->Form->expiration('StripeBilling.source.exp_month','StripeBilling.card.exp_year'); ?>
	<?= $this->Form->input("StripeBilling.source.name", array('label'=>'Cardholder Name','class'=>'width225')); ?>

	<?= $this->Form->hidden("StripeBilling.plan",array('value'=>$plan)); ?>


	<? if(!empty($setup)) { ?>
		<?= $this->Form->submit("Save Payment Information",array('div'=>'input center_text')); ?>
	<? } else { ?>
		<?= $this->Form->save("Save Payment Information",array('cancel'=>array('action'=>'view_billing'),'update'=>'PaymentDetails')); ?>
	<? } ?>
</div>


	<?= $this->Form->end(); ?>
</div>

