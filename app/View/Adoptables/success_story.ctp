<? $this->assign("page_title", $adoptable['Adoptable']['name']. " Success Story"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("left_admin_controls"); ?>
<? $this->end(); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All success stories", array("controller"=>"adoption_stories","action"=>"index")); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->add("Add story update", array("user"=>1,"controller"=>"adoption_stories","action"=>"add",'adoptable_id'=>$adoptable['Adoptable']['id'])); ?>
<? } ?>
<? $this->end(); ?>

<div class="pages view">
	<?= $this->element("Rescue.../Adoptables/stories"); ?>
</div>

