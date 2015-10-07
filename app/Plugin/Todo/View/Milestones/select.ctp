<?
	$label_alt = "(or ".$this->Html->link("create new milestone",array('controller'=>'milestones','action'=>'add'),array('update'=>'Milestone')) . ")";
	if(empty($selected)) { $selected = null; }
	if(!empty($this->data['Task']['milestone_id'])) { $selected =  $this->data['Task']['milestone_id']; } # For initial load below.
?>
<?= $this->Form->input("Task.milestone_id", array('options'=>$milestones,'selected'=>$selected,
	'label'=>"Milestone", 'label_alt'=>$label_alt, 'empty'=>'- None -',
	));
?>
