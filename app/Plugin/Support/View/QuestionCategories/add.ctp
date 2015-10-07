<!-- might be able to migrate 'adding' to use this modal too... -->
<? $adding = empty($this->data['QuestionCategory']['id']); ?>
<?
	$label_alt = null;
	if(!empty($questionCategories))
	{
		$label_alt = "<br/>(or ".$this->Html->link("select existing category",array('action'=>'select'),array('update'=>'QuestionCategory')).")";
	}

?>
<!-- we have to add a location WITH the event, since we cant handle nested forms -->
<div class="QuestionCategory">
	<?= $this->Form->hidden('QuestionCategory.id'); ?>
	<?= $this->Form->input('QuestionCategory.name', array('label'=>"Category name",'label_alt'=>$label_alt)); ?>

	<div class="clear"></div>
</div>
