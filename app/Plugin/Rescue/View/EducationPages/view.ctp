<? $id = !empty($educationPage["EducationPage"]["id"]) ? $educationPage["EducationPage"]["id"] : ""; ?>
<? $this->assign("page_title", $educationPage['EducationPage']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
	<? if(!empty($educationPage['Parent']['idurl'])) { ?>
		<?= $this->Html->back("All Pages", array("action"=>"view",$educationPage['Parent']['idurl'])); ?>
	<? } else { ?>
		<?= $this->Html->back("All Pages", array("action"=>"index")); ?>
	<? } ?>
<? if($this->Site->can_edit($educationPage['EducationPage'])) { ?>
	<?= $this->Html->blink("edit", "Edit Page", array("user"=>1,"action"=>"edit",$id)); ?>
<? } ?>
<? $this->end(); ?>

<?#= $this->Publishable->publish(); ?>

<div class="pages view">
<div class=''>
	<div align='right'>
		<?= !empty($educationPage['EducationPage']['created']) ? $this->Time->monthdy($educationPage['EducationPage']['created']) : null; ?>
	</div>

	<?= $this->element("PagePhotos.view"); ?>

	<div class='medium minheight300 padding25'>
		<?php echo ($educationPage['EducationPage']['content']); ?>
	</div>

	<div class='clear'></div>
</div>

<?/* NOT YET, KEEP EVERYTHING BASIC AND TOP LEVEL 
<div class=''>
<? if($this->Site->can_edit($educationPage['EducationPage'])) { # By default, only owner can add sub page - and then can re-assign ?>
	<div class='right'>
		<?= $this->Html->blink("add", "Add subpage", array("user"=>1,"action"=>"add",'parent_id'=>$id)); ?>
		<? if(count($educationPage['Subpage']) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>
	<h3>More information</h3>
	<? if(!empty($educationPage['Subpage'])) { ?>
		<?= $this->element("../EducationPages/list", array('pages'=>$educationPage['Subpage'])); ?>
	<? } ?>
</div>
<? if($this->Site->can_edit($educationPage['EducationPage'])) { ?>
<script>
$('#Subpage_sorter').sorter('.pagelist',{ prefix: "admin" });
</script>
<? } ?>
<? */ ?>

</div>

