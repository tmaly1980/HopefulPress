<? $this->assign("page_title", "Foster Details"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All",array('action'=>'index')); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Update",array('admin'=>1,'action'=>'status',$foster['Foster']['id']),array('title'=>'Update foster details','Xclass'=>'dialog')); ?>
<? } ?>
<? $this->end("title_controls"); ?>

<!-- tweak keys/sections for readability -->

<?= $this->element("Rescue.../Fosters/details"); ?>

