<? 
$this->layout = 'plain';
$model = $this->Form->defaultModel;
$emailField = Configure::read("User.emailField");
if(empty($emailField)) { $emailField = 'email'; }
$passField = Configure::read("User.passField");
if(empty($passField)) { $passField = 'password'; }
?>
<? $this->assign("container_class", "maxwidth700"); ?>
<? $id = !empty($this->data[$model]['id']) ? $this->data[$model]['id'] : null; ?>
<? $this->assign("page_title", "Setup Your Account"); ?>

<div class='users form border lightgreybg '>
	<?= $this->Form->create($this->Form->defaultModel,array('autocomplete'=>'off','id'=>"UserForm")); ?>
		<?= $this->Form->input('id'); ?>

		<? if(empty($id)) { ?>
		<?= $this->Form->input($emailField,array('validate'=>true,'required'=>true)); ?>
		<? } ?>

		<div class='alert alert-info'>
		Please create a password for signing in to your account
		</div>

		<?= $this->Form->input($passField,array('type'=>'password','value'=>'','label'=>'Create a password','required'=>true,'data-bv-notEmpty'=>'true','data-bv-stringLength'=>'true','data-bv-stringLength-min'=>8)); ?>
		<?= $this->Form->input($passField.'2',array('type'=>'password','value'=>'','label'=>'Confirm password','required'=>true,'data-bv-identical'=>'true','data-bv-identical-field'=>'data[User][Password]','data-bv-identical-message'=>'Passwords do not match')); ?>


		<? if(empty($this->request->data[$model]['first_name'])) { ?>
		<?= $this->Form->input('first_name',array('label'=>'First Name','data-bv-notEmpty'=>'true')); ?>
		<? } ?>
		<? if(empty($this->request->data[$model]['last_name'])) { ?>
		<?= $this->Form->input('last_name',array('label'=>'Last Name','data-bv-notEmpty'=>'true')); ?>
		<? } ?>

		<?= $this->Form->save("Continue",array('cancel'=>false)); ?>
	<?= $this->Form->end(); ?>
</div>
