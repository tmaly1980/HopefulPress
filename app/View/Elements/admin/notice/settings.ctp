<? if($this->Rescue->admin() && !in_array($this->layout,array('admin','plain'))) { # Non-sensical. ?>
<div class='alert alert-info'>
	Customize your rescue pages and design by going to <?= $this->Html->settings("Rescue Details", array('controller'=>'rescue','action'=>'edit')); ?>
</div>
<? } ?>
