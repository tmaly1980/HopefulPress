<? $id = !empty($this->data['User']['id']) ? $this->data['User']['id'] : null; ?>
<? $this->assign("title", $id?"Update User Account".(!empty($this->data['User']['disabled']) ? " &mdash; DISABLED":""):"Create User Account"); ?>

<? if(!empty($id) && $this->Admin->site_admin() && !$this->Admin->me($id)) { ?>
<? $this->start("title_controls"); ?>
<? if(!empty($this->data['User']['disabled'])) { ?>
	<?= $this->Html->link("Re-Enable Account", array('action'=>'enable',$id), array('class'=>'color orange')); ?>
<? } else { ?>
	<?= $this->Html->link("Disable Account", array('action'=>'disable',$id), array('class'=>'color orange','confirm_title'=>'Disable Account','confirm'=>"Are you sure you want to disable this person's account? They will no longer be able to sign in unless you re-enable their account.")); ?> 
<? } ?>
	<?= $this->Html->link("Remove Account", array('action'=>'delete',$id), array('class'=>'color red','confirm_title'=>'Remomve Account Permanently','confirm'=>"Are you sure you want to remove this account permanently? The user will not be able to log in anymore unless you create a new account.")); ?> 
<? $this->end(); ?>
<? } ?>

<div class="users form">
<?php echo $this->Form->create('User'); ?>
<? if(empty($id)) {?>
<div class='overview'>
	Create a user account for someone to let them add their own content to the website. They will receive an email with further instructions on how to sign in.
</div>
<? } ?>
<table width="100%">
<tr><td width="50%">
	<?= $this->Form->input("id"); ?>
	<?= $this->Form->input("email", array('tabindex'=>1,'label'=>'Their Email','required'=>1,'ghost'=>'joe@example.com','class'=>'width300 font16','note'=>'Their email is used to sign in')); ?>

	<?= $this->Form->input("first", array('label'=>'Their First Name','class'=>'width300 font16')); ?>
	<?= $this->Form->input("last", array('label'=>'Their Last Name','class'=>'width300 font16','note'=>"If you don't know their last name, just keep it blank")); ?>

	<? if($this->Admin->site("domain") && $this->Admin->site("email_enabled")) { ?>
	<? $domain = $this->Admin->site("domain"); ?>
	<?= $this->Form->input("email_enabled", array('label'=>"Enable webmail account")); ?>
	<div class='note'>ex: <i>username@<?= $domain ?></i></div>
	<div id="EmailDetails" style="<?= empty($this->data['User']['email_enabled']) ? "display:none;":"" ?>">
		<?= $this->Form->input("username", array('label'=>false,'ghost'=>'username','after'=>"@$domain",'note'=>'Choose a username for their email','size'=>12,'style'=>'text-align: right;')); ?>
	</div>
	<script>
	j('#UserEmailEnabled').click(function() { j('#EmailDetails').toggle(j('#UserEmailEnabled').is(":checked")); });
	</script>
	<? } ?>

<? if(!empty($memberPage['MemberPage']['contact_list_enabled'])) { ?>
	<?= $this->Form->input("share_contact_information", array('id'=>'ShareContactInformation')); ?>
	<div id='ContactInfo' style="<?= $this->Model->field("share_contact_information") ? "" : "display: none;" ?>">
	<table width="100%">
	<tr><td width="50%">
	<?= $this->Form->input("home_phone", array('class'=>'width90p')); ?>
	<?= $this->Form->input("cell_phone", array('class'=>'width90p')); ?>
	</td><td>
	<?= $this->Form->input("work_phone", array('class'=>'width90p')); ?>
	</td></tr>
	</table>
	<?= $this->Form->input("address", array('class'=>'width90p')); ?>
	</div>

	<script>
		j('#ShareContactInformation').click(function() {
			if(j(this).is(":checked")) { 
				j('#ContactInfo').show();
			} else { 
				j('#ContactInfo').hide();
			}
		});
	</script>
<? } ?>
</td><td>
	<?= $this->Form->input("administrator", array('type'=>'radio','legend'=>'Level of access', 'options'=>array(0=>'Contributor',1=>'Administrator'),'default'=>1)); ?>
	<div class='tip'>
		<b>Contributors</b> can add and manage their own content on the website. Give the user <b>contributor</b> level access if you just want them to add their own content but not change everything on the website.
		<br/>
		<br/>
		<b>Administrators</b> can update other people's content, create more users, and customize the homepage, contact us and about us pages. Be careful who you give <b>administrator</b> level access to.
	</div>
<? if(empty($id)) { ?>
	<?= $this->Form->input("Email.message", array('type'=>'textarea','rows'=>5,'cols'=>40,'label'=>'Message (optional) sent with email invite')); ?>

	<div class='note'>An email will be sent to the user for further instructions. They will be able to set their own password.
	</div>
<? } else { ?>
	<?= $this->Html->link("Send invite email again", array('action'=>'send_invite',$id), array('class'=>'color')); ?> to reset password

<? } ?>
</td>
</tr></table>

<?= $this->Form->save('Create User Account/Update User Acccount'); ?>

<?php echo $this->Form->end(); ?>
</div>
