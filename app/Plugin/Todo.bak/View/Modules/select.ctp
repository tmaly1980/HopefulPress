<?
	$label_alt = "(or ".$this->Html->link("create new module",array('controller'=>'modules','action'=>'add'),array('update'=>'Module')) . ")";
	if(empty($selected)) { $selected = null; }
	#  If need be, pass "selected" as parameter to requestAction/select/ID
?>
<?= $this->Form->input("Module.id", array('options'=>$modules,'selected'=>$selected,
	'label'=>"Module", 'label_alt'=>$label_alt, 'empty'=>'- None -',
	));
?>
