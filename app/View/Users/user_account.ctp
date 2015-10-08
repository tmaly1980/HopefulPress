<? $anon = $this->Html->user("anon"); ?>
<? if(empty($setup)) { $setup = null; } ?>
<? $this->assign("container_class", "maxwidth700"); ?>
<? $this->assign("page_title", $anon ? "Create Account" : "Account Details"); ?>

<div class='users form border lightgreybg '>
	<?= $this->Form->create("User",array('Xautocomplete'=>'off','id'=>"UserForm")); ?>
		<?= $this->Form->input('id',array('value'=>$this->Html->me())); # Force. ?>
		<?#= $this->element("PagePhotos.edit"); # ??? Grab copy from facebook if can? ?>
		<?#= $this->Form->input('Email',array('validate'=>true)); # TODO OK if duplicate if UserID match ?>

		<?= $this->Form->hidden('rescuer',array('value'=>1)); # FOR NOW.... ?>

		<?= $this->Form->input('first_name',array('validate'=>'notEmpty')); ?>
		<?= $this->Form->input('last_name',array('validate'=>'notEmpty')); ?>
		<? if(empty($setup)) { ?>
		<?= $this->Form->input('password',array('type'=>'password','value'=>'','label'=>($anon?'Create password':'Change password (optional)'),'tip'=>(!$anon?'Leave blank to keep existing password':''),
			'required'=>false,'data-bv-stringLength'=>'true','data-bv-stringLength-min'=>8,
			'data-bv-identical'=>'true'#,'data-bv-identical-field'=>'data[User][Password2]','data-bv-identical-message'=>'Passwords do not match'
		)); ?>
		<?= $this->Form->input('password2',array('type'=>'password','value'=>'','label'=>'Confirm password',
			'required'=>false,'data-bv-identical'=>'true','data-bv-threshold'=>4,'data-bv-identical-field'=>'data[User][password]','data-bv-identical-message'=>'Passwords do not match'
		)); ?>
		<? } ?>
		<?= $this->Form->save($anon?'Create Account':($setup?'Continue':'Save Account'),array('cancel'=>false)); ?>
	<?= $this->Form->end(); ?>
	<div class='clear'></div>
</div>
