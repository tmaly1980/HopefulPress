<? $userField = Configure::read("User.userField"); ?>
<? if(empty($userField)) { $userField = 'email'; } ?>
<? $this->assign("container_class", "maxwidth500"); ?>
<? $this->assign("page_title", "Password Reset"); ?>
<div class="users form border lightgreybg">
	<?= $this->Form->create("User"); ?>

	<?= $this->Form->input($userField,array('label'=>"Your ".ucfirst(Inflector::humanize($userField)))); ?>

	<div class='tip'>
		A message will be sent to the email above to help you sign back into your account
	</div>

	<?= $this->Form->save('Reset Password',array('cancel'=>false,'divclass'=>'center_align')); ?>
	<?= $this->Form->end(); ?>

</div>
