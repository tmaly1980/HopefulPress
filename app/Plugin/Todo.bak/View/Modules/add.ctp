<!-- might be able to migrate 'adding' to use this modal too... -->
<? $adding = empty($this->data['Module']['id']); ?>
<?
	$label_alt = null;
	if(!empty($modules))
	{
		$label_alt = "<br/>(or ".$this->Html->link("select existing module",array('action'=>'select'),array('update'=>'Module')).")";
	}

?>
<!-- we have to add a location WITH the event, since we cant handle nested forms -->
<div class="Module">
	<?= $this->Form->hidden('Module.id'); ?>
	<?= $this->Form->input('Module.title', array('label'=>"Module name",'label_alt'=>$label_alt)); ?>
	<div class="clear"></div>
</div>
