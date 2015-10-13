<? $id = $rescue['Rescue']['id']; ?>
<? $plan = $rescue['Rescue']['plan']; ?>
<? if(empty($plan)) { $plan = 'free'; } ?>
<b>Account Plan:</b>
<?= ucwords($plan); ?> 
<? if($id) { ?>
	| <?= $this->Html->link("Change",array('rescuer'=>1,'action'=>'plans')); ?>
	<? if($plan != 'free' && !empty($subscription)) { ?>
	<br/>
		<?= $this->Html->link("Update payment information",array('rescuer'=>1,'action'=>'billing')); ?>
	<? } ?>
<? } else { ?>
<div class='alert alert-info'>
	You'll be able to change your account plan at any time
</div>
<? } ?>

