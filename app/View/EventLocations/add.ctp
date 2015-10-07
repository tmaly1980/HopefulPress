<!-- might be able to migrate 'adding' to use this modal too... -->
<? $adding = empty($this->data['EventLocation']['id']); ?>
<?
	$label_alt = null;
	if(!empty($eventLocations))
	{
		$label_alt = "<br/>(or ".$this->Html->link("select existing location",array('action'=>'select'),array('update'=>'EventLocation')).")";
	}

?>
<!-- we have to add a location WITH the event, since we cant handle nested forms -->
<div class="EventLocation">
	<?= $this->Form->hidden('EventLocation.id'); ?>
	<?= $this->Form->input('EventLocation.name', array('label'=>"Location name",'label_alt'=>$label_alt)); ?>

	<?= $this->Html->link("Add location details", array('action'=>'edit'),array('class'=>'dialog post')); # 'post' class should carry over the name from here... ?>

	<div class="clear"></div>
</div>
