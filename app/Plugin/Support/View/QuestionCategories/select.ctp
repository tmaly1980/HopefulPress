<?
	$label_alt = "(or ".$this->Html->link("create new category",array('action'=>'add'),array('update'=>'QuestionCategory')) . ")";
	if(empty($selected)) { $selected = null; }
	if(!empty($this->data['Question']['question_category_id'])) { $selected =  $this->data['Question']['question_category_id']; } # For initial load below.
?>
<?= $this->Form->input("Question.question_category_id", array('options'=>$questionCategories,'selected'=>$selected,
	'label'=>"Question category", 'label_alt'=>$label_alt, 'empty'=>'- None -',
	));
?>
