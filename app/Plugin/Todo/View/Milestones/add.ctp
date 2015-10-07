<!-- might be able to migrate 'adding' to use this modal too... -->
<? $adding = empty($this->data['Milestone']['id']); ?>
<?
	$label_alt = null;
	if(!empty($milestones))
	{
		$label_alt = "<br/>(or ".$this->Html->link("select existing milestone",array('action'=>'select'),array('update'=>'Milestone')).")";
	}

?>
<!-- we have to add a location WITH the event, since we cant handle nested forms -->
<div class="Milestone">
	<?= $this->Form->hidden('Milestone.id'); ?>
	<?= $this->Form->input('Milestone.title', array('label'=>"Milestone name",'label_alt'=>$label_alt)); ?>
	<div class="clear"></div>
</div>
