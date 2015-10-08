<? #$this->layout = 'default'; ?>
<? $this->assign("search_disabled",true); ?>
<?#= $this->element("UserCore.login"); ?>
<? $this->assign("page_title", "You must have an account to continue"); ?>
<div class="users form border lightgreybg center">
	<?= $this->Session->flash("auth"); ?>
	<?= $this->Form->create("User"); ?>

	<?= $this->Form->input("email",array('Xsize'=>30)); ?>
	<?= $this->Form->input("password",array('type'=>'password','Xsize'=>30)); ?>
	<div class='row'>
	<div class='col-md-6 center_align'>
		<?= $this->Form->save("Sign In",array('name'=>'submit','divclass'=>'center_align','cancel'=>false)); ?>
	</div>
	<div class='col-md-6 center_align'>
		<?= $this->Form->submit("Create Account",array('name'=>'submit','divclass'=>'center_align','cancel'=>false,'class'=>'btn btn-primary')); ?>
	</div>
	</div>
	<br/>
	<div class='center_align'>
		<!-- let them sign in via fb for now -->
		<?= $this->Html->blink("fa-facebook","Sign in using Facebook",array('action'=>'facebook_login'),array('class'=>'btn btn-primary')); ?>
	</div>
</div>
<div class='center_align'>
	<?= $this->Html->link("Forgot your password?", "/users/forgot", array('class'=>'')); ?>
</div>
