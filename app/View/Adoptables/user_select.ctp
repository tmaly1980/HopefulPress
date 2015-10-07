<?
	$label_alt = "(or ".$this->Html->link("create new adoptable",array('user'=>1,'action'=>'add'),array('update'=>'Adoptable')) . ")";
	if(empty($selected)) { $selected = null; }
	if(!empty($this->data['AdoptionStory']['adoptable_id'])) { $selected =  $this->data['AdoptionStory']['adoptable_id']; } # For initial load below.
?>
<?= $this->Form->input("AdoptionStory.adoptable_id", array('options'=>$adoptables,'selected'=>$selected,
	'label'=>"Adoptable", 'label_alt'=>$label_alt, 'empty'=>'- Unknown -',
	));
?>
