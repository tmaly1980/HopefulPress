<? $anon = $this->Html->user("anon"); ?>
<? #$this->layout = 'admin'; ?>
<? $this->assign("container_class", "maxwidth700"); ?>
<? $this->assign("page_title", $anon ? "Create Account" : "Update Account"); ?>
<? $this->start("admin_controls"); ?>
	<?#= $this->Html->blink('back',"Back to Grower", array('controller'=>'growers','action'=>'index')); ?>
<? $this->end(); ?>

<div class='users form border lightgreybg '>
	<?= $this->Form->create("User",array('Xautocomplete'=>'off','id'=>"UserForm")); ?>
		<?= $this->Form->input('id'); ?>
		<?= $this->element("PagePhotos.edit"); ?>
		<?#= $this->Form->input('Email',array('validate'=>true)); # TODO OK if duplicate if UserID match ?>
		<?= $this->Form->input('first_name',array('validate'=>'notEmpty')); ?>
		<?= $this->Form->input('last_name',array('validate'=>'notEmpty')); ?>
		<?= $this->Form->input('password',array('type'=>'password','value'=>'','label'=>($anon?'Create password':'Change password (optional)'),'tip'=>(!$anon?'Leave blank to keep existing password':''),
			'required'=>false,'data-bv-stringLength'=>'true','data-bv-stringLength-min'=>8,
			'data-bv-identical'=>'true'#,'data-bv-identical-field'=>'data[User][Password2]','data-bv-identical-message'=>'Passwords do not match'
		)); ?>
		<?= $this->Form->input('password2',array('type'=>'password','value'=>'','label'=>'Confirm password',
			'required'=>false,'data-bv-identical'=>'true','data-bv-threshold'=>4,'data-bv-identical-field'=>'data[User][password]','data-bv-identical-message'=>'Passwords do not match'
		)); ?>
		<?= $this->Form->save($anon?'Create Account':'Update Account'); ?>
	<?= $this->Form->end(); ?>
	<div class='clear'></div>
</div>
