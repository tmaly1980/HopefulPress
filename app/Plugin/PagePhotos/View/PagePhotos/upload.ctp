<? extract($this->PagePhoto->config(compact('parentClass','photoModel'))); ?>
<script>$.dialogtitle("Add a <?=$thing?> from your computer");</script>
<div class='form <?=$photoModel?>Upload'>
<?= $this->Form->create($photoModel, array('url'=>array('plugin'=>$plugin,'controller'=>$controller,'action'=>'upload',$parentClass,$photoModel,$model_id), 'type'=>'file','class'=>'json','id'=>"{$photoModel}UploadForm")); ?>

	<?= $this->Form->hidden("$photoModel.width", array('value'=>'300')); ?>

	<?= $this->Form->input("$photoModel.file", array('label'=>'Choose a picture from your computer','type'=>'file','onChange'=>"$('#{$photoModel}UploadForm').submit();")); ?>

	<?#= $this->Form->save("Upload", array('modal'=>true)); ?>
	<br/>
	<script>
	//$.dialogbuttons(['cancel','save']);
	</script>
<?= $this->Form->end(); ?>
</div>
