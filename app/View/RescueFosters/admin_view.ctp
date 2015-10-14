<? $this->assign("page_title", "Foster Details"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All",array('action'=>'index')); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Update",array('admin'=>1,'action'=>'edit',$rescueFoster['RescueFoster']['id']),array('title'=>'Update foster details')); ?>
<? } # FOR NOW, let the site update foster profiles... until we emphasize foster profiles/users more. ?>
<? $this->end("title_controls"); ?>

<!-- tweak keys/sections for readability -->

<?= $this->element("../RescueFosters/details"); ?>

