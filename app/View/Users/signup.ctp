<? $this->assign("page_title", "Your personal details"); ?>
<? $this->assign("search_disabled",true); ?>
<div class='alert alert-info'>
	Please provide some details about yourself before continuing
</div>

<div class='users form border lightgreybg maxwidth500 center'>
	<?= $this->Form->create("User",array('Xautocomplete'=>'off','id'=>"UserForm",'url'=>"/users/signup")); ?>
		<?= $this->Form->input('id',array('value'=>$this->Html->me())); # Force. ?>
		<?#= $this->element("PagePhotos.edit"); # ??? Grab copy from facebook if can? ?>
		<?= $this->Form->input('first_name',array('required'=>1,'validate'=>'notEmpty')); ?>
		<?= $this->Form->input('last_name',array('required'=>1,'validate'=>'notEmpty')); ?>

		<?= $this->Form->hidden('rescuer',array('value'=>1)); # FOR NOW.... ?>

		<?= $this->Form->input('email',array('required'=>true)); # TODO OK if duplicate if UserID match ?>
		<?= $this->Form->input('password',array('type'=>'password','label'=>'Create password',
			'required'=>true,'data-bv-stringLength'=>'true','data-bv-stringLength-min'=>8,
			#'data-bv-identical'=>'true'
		)); ?>
		<?#= $this->Form->input('password2',array('type'=>'password','value'=>'','label'=>'Confirm password',
			#'required'=>false,'data-bv-identical'=>'true','data-bv-threshold'=>4,'data-bv-identical-field'=>'data[User][password]','data-bv-identical-message'=>'Passwords do not match'
		#)); ?>
		<?#= $this->Form->submit("Create Account",array('name'=>'submit','class'=>'btn btn-primary')); ?>
		<?= $this->Form->save("Create Account"); # validator and/or browser dont seem to like input type=submit when disabled ?>
	<?= $this->Form->end(); ?>
	<div class='clear'></div>
</div>
