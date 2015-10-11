<? $this->assign("page_title", $educationIndex['EducationIndex']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Site->can_edit($educationIndex['EducationIndex'])) { ?>
<div  class='controls'>
	<?= $this->Html->blink("edit", "Edit Overview", array("user"=>1,"action"=>"edit")); ?>
	<?= $this->Html->blink("add", "Add page", array("plugin"=>'rescue',"user"=>1,"controller"=>"education_pages","action"=>"add"),array('class'=>'btn-warning white')); ?>
</div>
<? } ?>
<? $this->end(); ?>

<?#= $this->Publishable->publish(); ?>

<div class="pages view">
<div class=''>
	<? if($this->Html->can_edit())  { ?>
	<div class='alert alert-info'>
		You can add educational/informational pages pertaining to adoption, fostering, and pet ownership here.
	</div>
	<? } ?>

	<?= $this->element("PagePhotos.view"); ?>

	<? if(empty($educationIndex['EducationIndex']['content']) && $this->Html->me()) { ?>
	<div class='alert alert-info'>
		<?= $this->Html->link("Add an introduction", array('user'=>1,'action'=>'edit'),array('class'=>'btn btn-xs btn-primary')); ?>
		<br/>
	</div>
	<? } ?>

	<? if(!empty($educationIndex['EducationIndex']['content'])) { ?>
	<div class='medium padding25'>
		<?php echo ($educationIndex['EducationIndex']['content']); ?>
	</div>
	<? } ?>

	<div class='clear'></div>
</div>

<div class=''>
<? if($this->Site->can_edit()) { # By default, only owner can add sub page - and then can re-assign ?>
	<div class='right'>
		<?= $this->Html->blink("add", "Add page", array("plugin"=>'rescue',"user"=>1,"controller"=>"education_pages","action"=>"add")); ?>
		<? if(count($pages) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>
	<h3>More information</h3>
	<? if(!empty($pages)) { ?>
		<?= $this->element("Rescue.../EducationPages/list", array('pages'=>$pages)); ?>
	<? } else { ?>
		<div class='nodata'>There are no pages yet</div>
	<? } ?>
</div>
<? if($this->Site->can_edit()) { ?>
<script>
$('#Subpage_sorter').sorter('.pagelist',{ prefix: "user",controller:"education_pages" });
</script>
<? } ?>

	

</div>

