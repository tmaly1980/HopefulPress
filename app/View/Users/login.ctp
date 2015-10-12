<? #$this->layout = 'default'; ?>
<? $this->assign("search_disabled",true); ?>
<?#= $this->element("UserCore.login"); ?>
<? $this->assign("container_class","maxwidth550 center"); ?>
<? $this->assign("page_title", $this->Rescue->dedicated()?"Sign in to continue":"Sign in or create an account to continue");  ?>
<div class="users form border lightgreybg ">
	<?= $this->Session->flash("auth"); ?>
	<?= $this->Form->create("User"); ?>

	<? if(!$this->Rescue->dedicated()) { ?>
	<div class='center_align'>
		<!-- let them sign in via fb for now -->
		<?= $this->Html->blink("fa-facebook","Sign in using Facebook",array('action'=>'facebook_login'),array('class'=>'btn btn-primary')); ?>
		<br/>
		<br/>
	</div>
	<h3>Or sign in/create an account:</h3>
	<? } ?>

	<?= $this->Form->input("email",array('Xsize'=>30)); ?>
	<?= $this->Form->input("password",array('type'=>'password','Xsize'=>30)); ?>
	<? if(!$this->Rescue->dedicated()) { ?>
	<div class='row'>
	<div class='col-md-6 center_align'>
	<? } ?>
		<?= $this->Form->save("Sign In",array('name'=>'submit','divclass'=>'center_align','cancel'=>false)); ?>
	<? if(!$this->Rescue->dedicated()) { ?>
	</div>
	<div class='col-md-6 center_align'>
		<?= $this->Form->submit("Create Account",array('name'=>'submit','divclass'=>'center_align','cancel'=>false,'class'=>'btn btn-primary')); ?>
	</div>
	</div>
	<? } ?>
	<br/>
</div>
<div class='center_align'>
	<?= $this->Html->link("Forgot your password?", "/users/forgot", array('class'=>'')); ?>
</div>
