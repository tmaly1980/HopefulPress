<? $this->assign("page_title", "Volunteer Details"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All",array('action'=>'index')); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Update",array('admin'=>1,'action'=>'edit',$volunteer['Volunteer']['id']),array('title'=>'Update volunteer details')); ?>
<? } ?>
<? $this->end("title_controls"); ?>

<!-- tweak keys/sections for readability -->

<?= $this->element("Rescue.../Volunteers/details"); ?>

