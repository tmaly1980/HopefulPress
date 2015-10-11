<?= $this->assign("page_title", "Volunteers/Applicants");  ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add volunteer/applicant",array('admin'=>1,'action'=>'add')); ?>
<? $this->end("title_controls"); ?>

<div class='index'>

<? if(isset($applicants)) { ?>
<h3>Volunteer Applicants</h3>
<div class='alert alert-info'>
	The following volunteers have recently submitted applications via your website. You can create user accounts for volunteers to contribute content by setting their status as 'Active'.
</div>
	<? if(empty($applicants)) { ?>
	<div class='nodata'>No new volunteer applicants</div>
	<? } else { ?>
		<?= $this->element("../Volunteers/list",array('volunteers'=>$applicants)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($volunteers)) { ?>
<h3>Active Volunteers</h3>
<div class='alert alert-info'>
	These are volunteers currently working for your organization. You can optionally create user accounts for them to contribute content to your website.
</div>
	<? if(empty($volunteers)) { ?>
	<div class='nodata'>No active volunteers</div>
	<? } else { ?>
		<?= $this->element("../Volunteers/list",array('volunteers'=>$volunteers)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($inactives)) { ?>
<h3>Inactive Volunteers</h3>
<div class='alert alert-info'>
	These are former volunteers no longer working for your organization. Their user accounts are automatically disabled.
</div>
	<? if(empty($inactives)) { ?>
	<div class='nodata'>No inactive volunteers</div>
	<? } else { ?>
		<?= $this->element("../Volunteers/list",array('volunteers'=>$inactives)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($rejecteds)) { ?>
<h3>Rejected Volunteers</h3>
<div class='alert alert-info'>
	These are volunteer applicants you've chosen to ignore
</div>
	<? if(empty($rejecteds)) { ?>
	<div class='nodata'>No rejected volunteer applicants</div>
	<? } else { ?>
		<?= $this->element("../Volunteers/list",array('volunteers'=>$rejecteds)); ?>
	<? } ?>
	<br/>
<? } ?>

</div>
