<?= $this->assign("page_title", "Fosters/Applicants");  ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add foster/applicant",array('admin'=>1,'action'=>'add')); ?>
<? $this->end("title_controls"); ?>

<div class='index'>

<? if(isset($applicants)) { ?>
<h3>Foster Applicants</h3>
<div class='alert alert-info'>
	The following fosters have recently submitted applications via your website. You can create user accounts for fosters to contribute content by setting their status as 'Active'.
</div>
	<? if(empty($applicants)) { ?>
		<div class='nodata'>No recent foster applicants</div>
	<? } else { ?>
	<?= $this->element("../RescueFosters/list",array('fosters'=>$applicants)); ?>
	<? } ?>
<? } ?>

<? if(!empty($fosters)) { ?>
<h3>Active Fosters (Online)</h3>
<div class='alert alert-info'>
	These are fosters currently working for your organization and have user accounts for them to contribute content to your website.
</div>
	<?= $this->element("../RescueFosters/list",array('fosters'=>$fosters,'online'=>1)); ?>
<? } ?>

<? if(!empty($offlineFosters)) { ?>
<h3>Active Fosters (Offline)</h3>
<div class='alert alert-info'>
	These are fosters currently working for your organization but do not have access to update your website.
</div>
	<?= $this->element("../RescueFosters/list",array('fosters'=>$offlineFosters)); ?>
<? } ?>

<? if(!empty($inactives)) { ?>
<h3>Inactive Fosters</h3>
<div class='alert alert-info'>
	These are former fosters no longer working for your organization. Their user accounts are automatically disabled.
</div>
	<?= $this->element("../RescueFosters/list",array('fosters'=>$inactives)); ?>
<? } ?>

<? if(!empty($ignoreds)) { ?>
<h3>Ingored Fosters</h3>
<div class='alert alert-info'>
	These are foster applicants you've chosen to ignore
</div>
	<?= $this->element("../RescueFosters/list",array('fosters'=>$ignoreds)); ?>
<? } ?>

</div>
