<?= $this->assign("page_title", "Volunteers/Applicants");  ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add volunteer/applicant",array('admin'=>1,'action'=>'add')); ?>
<? $this->end("title_controls"); ?>

<div class='index'>

<? if(!empty($applicants)) { ?>
<h3>Volunteer Applicants</h3>
<div class='alert alert-info'>
	The following volunteers have recently submitted applications via your website. You can create user accounts for volunteers to contribute content by setting their status as 'Active'.
</div>
	<?= $this->element("../RescueVolunteers/list",array('volunteers'=>$applicants)); ?>
<? } ?>

<? if(!empty($volunteers)) { ?>
<h3>Active Volunteers (Online)</h3>
<div class='alert alert-info'>
	These are volunteers currently working for your organization and have user accounts for them to contribute content to your website.
</div>
	<?= $this->element("../RescueVolunteers/list",array('volunteers'=>$volunteers,'online'=>1)); ?>
<? } ?>

<? if(!empty($offlineVolunteers)) { ?>
<h3>Active Volunteers (Offline)</h3>
<div class='alert alert-info'>
	These are volunteers currently working for your organization but do not have access to update your website.
</div>
	<?= $this->element("../RescueVolunteers/list",array('volunteers'=>$offlineVolunteers)); ?>
<? } ?>

<? if(!empty($inactives)) { ?>
<h3>Inactive Volunteers</h3>
<div class='alert alert-info'>
	These are former volunteers no longer working for your organization. Their user accounts are automatically disabled.
</div>
	<?= $this->element("../RescueVolunteers/list",array('volunteers'=>$inactives)); ?>
<? } ?>

<? if(!empty($ignoreds)) { ?>
<h3>Ingored Volunteers</h3>
<div class='alert alert-info'>
	These are volunteer applicants you've chosen to ignore
</div>
	<?= $this->element("../RescueVolunteers/list",array('volunteers'=>$ignoreds)); ?>
<? } ?>

</div>
