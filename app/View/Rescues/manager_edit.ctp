<? $id = !empty($this->request->data['Rescue']['id']) ? $this->request->data['Rescue']['id'] : null; ?>
<? $this->assign("page_class", "center col-md-6"); ?>
<? $this->assign("page_title", empty($id)?"Add Website":"Edit Website"); ?>
<? $this->start("title_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->delete("Disable website", array('action'=>'disable',$id)); ?>
<? } ?>
<? $this->end(); ?>
<div class="sites form">
	<?= $this->element("../Rescues/form"); ?>
</div>
