<? $this->assign("page_title", "Adoption Request Details"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All",array('action'=>'index')); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Update Status",array('user'=>1,'action'=>'status',$adopter['Adopter']['id']),array('title'=>'Change application status','class'=>'dialog')); ?>
<? } ?>
<? $this->end("title_controls"); ?>

<!-- tweak keys/sections for readability -->

<?= $this->element("../Adopters/details"); ?>

