<? $userField = Configure::read("User.userField"); ?>
<? $passwordField = Configure::read("User.passwordField"); ?>

<? $this->assign("container_class", "Xmaxwidth500"); ?>
<? $this->assign("page_title", "Sign In"); ?>
<div class="users form border lightgreybg center">
	<?= $this->Session->flash("auth"); ?>
	<?= $this->Form->create("User"); ?>

	<?= $this->Form->input(!empty($userField)?$userField:'email',array('Xsize'=>30)); ?>
	<?= $this->Form->input(!empty($passwordField)?$passwordField:'password',array('type'=>'password','Xsize'=>30)); ?>
	<?= $this->Form->save("Sign In",array('divclass'=>'center_align','cancel'=>false)); ?>
</div>
<div class='center_align'>
	<?= $this->Html->link("Forgot your password?", "/users/forgot", array('class'=>'')); ?>
</div>
