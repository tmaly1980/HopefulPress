<div class='form'>
	<?= $this->Form->create("Photo",array('json'=>1)); ?>
		<?= $this->Form->hidden('id'); ?>
		<div class='center_align'>
			<?= $this->Html->image(array('controller'=>'photos','action'=>'fullimage',$this->request->data['Photo']['id'],'200x200'),array('class'=>'maxwidth100p')); ?>
		</div>
		<?= $this->Form->input("caption",array('label'=>false,'placeholder'=>'Add a caption...','rows'=>3)); ?>
		<?= $this->Form->save("Update",array('cancel_js'=>'$.dialogclose();')); ?>
	<?= $this->Form->end(); ?>
</div>
