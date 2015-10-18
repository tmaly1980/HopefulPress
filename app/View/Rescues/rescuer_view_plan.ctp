<? $id = $rescue['Rescue']['id']; ?>
<? $plan = $rescue['Rescue']['plan']; ?>
<? if(empty($plan)) { $plan = 'free'; } ?>
<b>Account Plan:</b>
<?= Inflector::humanize($plan); ?> 
<? if($id) { ?>
	<br/><?= $this->Html->link("Update plan / payment information",array('admin'=>1,'plugin'=>'stripe','controller'=>'stripe_billing','action'=>'view')); ?>
<? } else { ?>
<div class='alert alert-info'>
	You'll be able to change your account plan at any time
</div>
<? } ?>

