<?
	$label_alt = "(or ".$this->Html->link("create new release",array('controller'=>'releases','action'=>'add'),array('update'=>'Release')) . ")";
	if(empty($selected)) { $selected = null; }
	if(!empty($this->data['Task']['release_id'])) { $selected =  $this->data['Task']['release_id']; } # For initial load below.
?>
<?= $this->Form->input("Release.id", array('options'=>$releases,'selected'=>$selected,
	'label'=>"Release", 'label_alt'=>$label_alt, 'empty'=>'- None -',
	));
?>
