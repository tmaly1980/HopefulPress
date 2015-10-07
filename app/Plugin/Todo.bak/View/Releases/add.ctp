<!-- might be able to migrate 'adding' to use this modal too... -->
<? $adding = empty($this->data['Release']['id']); ?>
<?
	$label_alt = null;
	if(!empty($releases))
	{
		$label_alt = "<br/>(or ".$this->Html->link("select existing release",array('action'=>'select'),array('update'=>'Release')).")";
	}

?>
<!-- we have to add a location WITH the event, since we cant handle nested forms -->
<div class="Release">
	<?= $this->Form->hidden('Release.id'); ?>
	<?= $this->Form->input('Release.title', array('label'=>"Release name",'label_alt'=>$label_alt)); ?>
	<div class="clear"></div>
</div>
