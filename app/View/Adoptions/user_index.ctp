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
		<?= $this->element("../Adoptions/list",array('adoptions'=>$received)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($pending)) { ?>
<h3>Pending Applications</h3>
<div class='alert alert-info'>
	The following adoption applications are pending review.
</div>
	<? if(empty($pending)) { ?>
	<div class='nodata'>No pending applications</div>
	<? } else { ?>
		<?= $this->element("../Adoptions/list",array('adoptions'=>$pending)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($accepted)) { ?>
<h3>Accepted Adoption Applications</h3>
<div class='alert alert-info'>
	The following adoption applications have been accepted
</div>
	<? if(empty($accepted)) { ?>
	<div class='nodata'>No accepted applications</div>
	<? } else { ?>
		<?= $this->element("../Adoptions/list",array('adoptions'=>$accepted)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($deferred)) { ?>
<h3>Deferred Applications</h3>
<div class='alert alert-info'>
	The following adoption applications have been deferred for later.
</div>
	<? if(empty($deferred)) { ?>
	<div class='nodata'>No deferred applications</div>
	<? } else { ?>
		<?= $this->element("../Adoptions/list",array('adoptions'=>$deferred)); ?>
	<? } ?>
	<br/>
<? } ?>

<? if(isset($denied)) { ?>
<h3>Denied Applications</h3>
<div class='alert alert-info'>
	The following adoption applications have been denied.
</div>
	<? if(empty($denied)) { ?>
	<div class='nodata'>No denied applications</div>
	<? } else { ?>
		<?= $this->element("../Adoptions/list",array('adoptions'=>$denied)); ?>
	<? } ?>
	<br/>
<? } ?>

</div>
