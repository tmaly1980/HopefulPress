<?= $this->assign("page_title", "Adoptions/Applicants");  ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add adoption/applicant",array('user'=>1,'action'=>'add')); ?>
<? $this->end("title_controls"); ?>

<div class='index'>

<? if(isset($received)) { ?>
<h3>Received Applications</h3>
<div class='alert alert-info'>
	The following adoption applications have been recently submitted via your website.
</div>
	<? if(empty($received)) { ?>
	<div class='nodata'>No new adoptive applications</div>
	<? } else { ?>
		<?= $this->element("../Adopters/list",array('adoptions'=>$received)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(!empty($pending)) { ?>
<h3>Pending Applications</h3>
<div class='alert alert-info'>
	The following adoption applications are pending review.
</div>
	<?= $this->element("../Adopters/list",array('adoptions'=>$pending)); ?>
	<br/>
<? } ?>

<? if(!empty($accepted)) { ?>
<h3>Approved Adoption Applications</h3>
<div class='alert alert-info'>
	The following adoption applications have been approved
</div>
	<?= $this->element("../Adopters/list",array('adoptions'=>$approved)); ?>
	<br/>
<? } ?>

<? if(!empty($deferred)) { ?>
<h3>Deferred Applications</h3>
<div class='alert alert-info'>
	The following adoption applications have been deferred for later.
</div>
	<?= $this->element("../Adopters/list",array('adoptions'=>$deferred)); ?>
<? } ?>

<? if(!empty($denied)) { ?>
<h3>Denied Applications</h3>
<div class='alert alert-info'>
	The following adoption applications have been denied.
</div>
	<?= $this->element("../Adopters/list",array('adoptions'=>$denied)); ?>
<? } ?>

</div>
