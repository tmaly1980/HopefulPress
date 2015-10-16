<? # May as well just have two separate forms.... ?>
<div class='row'>
<div class='col-md-6'>
	<h3>Make a One-time Donation</h3>
	<?= $this->Paypal->create(); ?>
	<?
		$description = !empty($adoptable) ? 
			"Sponsorship for {$adoptable['Adoptable']['name']} to {$rescue['Rescue']['title']}"
			: "Donation to ".$rescue['Rescue']['title'];
	?>
	<? if(!empty($adoptable_id)) { ?>
		<?= $this->Form->hidden(false,array('name'=>'custom','value'=>$adoptable_id)); #($adoptable_id);#hidden("adoptable_id", array('value'=>$adoptable_id)); ?>
	<? } ?>
		<?= $this->Form->hidden(false,array('name'=>'business','value'=>$paypalCredentials['PaypalCredential']['paypal_email'])); ?>
		<?= $this->Form->hidden(false,array('name'=>'item_name','value'=>$description)); ?>

		<?= $this->Paypal->input('amount', array('default'=>$defaultAmount)); ?>
		<?= $this->Form->input("amount_option", array('type'=>'radio','options'=>$amounts,'default'=>$defaultAmount,'class'=>'DonateAmounts','div'=>'inline-radio')); ?>
		<?= $this->Form->input("other", array('div'=>'','label'=>false,'placeholder'=>'Other','class'=>'DonateOther','size'=>7)); ?>
		<div class='clear'></div>

		<?= $this->Form->submit("/donation/images/paypal-donate.png",array('class'=>'height50')); ?>
	<?= $this->Paypal->end(); ?>
</div>
<div class='col-md-6'>
	<h3>Make a Recurring (Monthly) Donation</h3>
	<?= $this->Paypal->create(false,array('recurring'=>true)); ?>
	<? if(!empty($adoptable_id)) { ?>
		<?= $this->Form->hidden(false,array('name'=>'custom','value'=>$adoptable_id)); #($adoptable_id);#hidden("adoptable_id", array('value'=>$adoptable_id)); ?>
	<? } ?>
		<?= $this->Form->hidden(false,array('name'=>'business','value'=>$paypalCredentials['PaypalCredential']['paypal_email'])); ?>
		<?= $this->Form->hidden(false,array('name'=>'item_name','value'=>"Recurring $description")); ?>

		<?= $this->Paypal->input('amount', array('name'=>'a3','default'=>$defaultAmount)); ?>
		<?= $this->Form->hidden(false,array('name'=>'p3','value'=>'1')); # Subscription duration ?>
		<?= $this->Form->hidden(false,array('name'=>'t3','value'=>'M')); # Subscription units, monthly ?>
		<?= $this->Form->hidden(false,array('name'=>'src','value'=>'1')); # Recurring (might be redundant) ?>

		<?= $this->Form->input("amount_option", array('type'=>'radio','options'=>$amounts,'default'=>$defaultAmount,'class'=>'DonateAmounts','div'=>'inline-radio')); ?>
		<?= $this->Form->input("other", array('label'=>false,'placeholder'=>'Other','class'=>'DonateOther','size'=>7,'div'=>'')); ?>

		<?= $this->Form->submit("/donation/images/paypal-subscribe.gif",array('class'=>'height50')); ?>
	<?= $this->Paypal->end(); ?>
</div>
</div>

