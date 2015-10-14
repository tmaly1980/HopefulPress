<? $id = !empty($page["VolunteerPage"]["id"]) ? $page["VolunteerPage"]["id"] : ""; ?>
<? $this->assign("page_title", $page['VolunteerPage']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->back("Volunteer Info", array("action"=>"index")); ?>
<? } ?>
<? if($this->Html->can_edit($page['VolunteerPage'])) { ?>
	<?= $this->Html->edit("Edit Page", array("user"=>1,"action"=>"edit",$id)); ?>
<? } ?>
<? $this->end(); ?>

<?#= $this->Publishable->publish(); ?>

<div class="pages view">
<div class=''>
	<div align='right'>
		<?= !empty($page['VolunteerPage']['created']) ? $this->Time->monthdy($page['VolunteerPage']['created']) : null; ?>
	</div>

	<?= $this->element("PagePhotos.view"); ?>

	<div class='medium minheight300 padding25'>
		<?php echo ($page['VolunteerPage']['content']); ?>
	</div>

	<div class='clear'></div>
</div>


	

</div>

