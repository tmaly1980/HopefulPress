<? $id = !empty($this->request->data['RescueVolunteer']['id']) ? $this->request->data['RescueVolunteer']['id'] : null; ?>
<? $this->assign("page_title", $id?"Update a volunteer":"Add a volunteer"); ?>
<? $this->start("title_controls"); ?>
<? if($id) { ?>
	<?= $this->Html->back("View volunteer", array('action'=>'view',$id)); ?>
<? } else { ?>
	<?= $this->Html->back("All volunteers", array('action'=>'index')); ?>
<? } ?>
<? $this->end(); ?>
<?= $this->element("../RescueVolunteers/edit"); ?>
