<? $this->assign("page_title", "Adoption Stories"); ?>
<? if($this->Site->can_edit()) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add Adoption Story", array("user"=>1,"action"=>"add")); ?>
<? $this->end(); ?>
<? } ?>

<div class='index'>
	<!-- should be GROUPED by adoptable_id.... ie when click on details, show all updates for that adoptable... ie /adoption/stories/adoptable_id -->
	<?= $this->element("Rescue.../AdoptionStories/list"); ?>
</div>
