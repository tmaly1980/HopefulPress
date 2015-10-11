<?= $this->assign("page_title", "Fosters/Applicants");  ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add foster parent/applicant",array('admin'=>1,'action'=>'add')); ?>
<? $this->end("title_controls"); ?>

<div class='index'>

<? if(isset($applicants)) { ?>
<h3>Foster Parent Applicants</h3>
<div class='alert alert-info'>
	The following have recently submitted applications via your website. You can create user accounts for foster parents to contribute content by setting their status as 'Active'.
</div>
	<? if(empty($applicants)) { ?>
	<div class='nodata'>No new foster applicants</div>
	<? } else { ?>
		<?= $this->element("../Fosters/list",array('fosters'=>$applicants)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($fosters)) { ?>
<h3>Active Foster Parents</h3>
<div class='alert alert-info'>
	These are foster parents currently working for your organization. You can optionally create user accounts for them to contribute content to your website.
</div>
	<? if(empty($fosters)) { ?>
	<div class='nodata'>No active fosters</div>
	<? } else { ?>
		<?= $this->element("../Fosters/list",array('fosters'=>$fosters)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($inactives)) { ?>
<h3>Inactive Foster Parents</h3>
<div class='alert alert-info'>
	These are former foster parents no longer working for your organization. Their user accounts are automatically disabled.
</div>
	<? if(empty($inactives)) { ?>
	<div class='nodata'>No inactive fosters</div>
	<? } else { ?>
		<?= $this->element("../Fosters/list",array('fosters'=>$inactives)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($rejecteds)) { ?>
<h3>Rejected Fosters</h3>
<div class='alert alert-info'>
	These are foster applicants you've chosen to ignore
</div>
	<? if(empty($rejecteds)) { ?>
	<div class='nodata'>No rejected foster applicants</div>
	<? } else { ?>
		<?= $this->element("../Fosters/list",array('fosters'=>$rejecteds)); ?>
	<? } ?>
	<br/>
<? } ?>

</div>
