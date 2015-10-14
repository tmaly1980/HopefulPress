<? $id = !empty($this->request->data['RescueFoster']['id']) ? $this->request->data['RescueFoster']['id'] : null; ?>
<? $this->assign("page_title", $id?"Update a foster":"Add a foster"); ?>
<? $this->start("title_controls"); ?>
<? if($id) { ?>
	<?= $this->Html->back("View foster", array('action'=>'view',$id)); ?>
<? } else { ?>
	<?= $this->Html->back("All fosters", array('action'=>'index')); ?>
<? } ?>
<? $this->end(); ?>
<?= $this->element("../RescueFosters/edit"); ?>
