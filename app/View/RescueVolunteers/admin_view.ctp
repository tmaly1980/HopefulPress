<? $this->assign("page_title", "Volunteer Details"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All",array('action'=>'index')); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Update",array('admin'=>1,'action'=>'edit',$rescueVolunteer['RescueVolunteer']['id']),array('title'=>'Update volunteer details')); ?>
<? } # FOR NOW, let the site update volunteer profiles... until we emphasize volunteer profiles/users more. ?>
<? $this->end("title_controls"); ?>

<!-- tweak keys/sections for readability -->

<?= $this->element("../RescueVolunteers/details"); ?>

