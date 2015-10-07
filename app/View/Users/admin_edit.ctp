<? $this->assign("container_class", "maxwidth900"); ?>
<? $id = !empty($this->data['User']['id']) ? $this->data['User']['id'] : null; ?>
<? $this->assign("page_title", !$id ? "Add User" : "Update User"); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->blink('back',"All Users", array('action'=>'index')); ?>
<? $this->end(); ?>

<div class='users form border lightgreybg '>
<?= $this->Form->create("User",array('autocomplete'=>'off','id'=>"UserForm")); ?>
<div class='row'>
	<div class='col-md-6'>
		<?= $this->Form->input('id'); ?>
		<?= $this->Form->input('email',array('validate'=>true)); ?>
		<?= $this->Form->input('first_name',array('data-bv-notEmpty'=>'true')); ?>
		<?= $this->Form->input('last_name',array('data-bv-notEmpty'=>'true')); ?>

		<? if($this->Site->get("email_enabled") && ($domain = $this->Site->get("domain"))) { ?>
			<?= $this->Form->input_group('username',array('after'=>"@$domain",'class'=>'right_align','label'=>'Webmail Account','note'=>"Users can access their webmail via http://mail.$domain/")); ?>
			<?= $this->Form->input("webmail_instructions",array('type'=>'checkbox','label'=>'Send webmail account details')); ?>
		<?  } ?>
	</div>
	<div class='col-md-6'>
		<?= $this->element("PagePhotos.edit"); ?>
	</div>
</div>
		<?= $this->Form->input('admin',array('label'=>'Grant administrator access')); ?>
		<div id='admin_help'>
			All users can:
			<ul>
				<li>Contribute news, events, photos (may show on the homepage)</li>
				<li>Add pages to existing topics</li>
				<li>Add and update their own links and downloads</li>
			</ul>

			Administrators can:
			<ul>
				<li>Create other users</li>
				<li>Add new topics</li>
				<li>Add, update and remove anyone's links and downloads</li>
				<li>Update the About, Contact, and Home pages</li> 
			</ul>
		</div>
		<script>$('#UserAdmin').addHelp('#admin_help');</script>

		<? 
		if(!empty($id)) { # give option to send additional sign-in instructions
		$invited = !empty($this->Form->data['User']['invited']) ?
				$this->Time->format($this->Form->data['User']['invited'], "%c") : null;
		?>
		<?= $this->Form->input("invite",array('type'=>'checkbox','label'=>'Email user with sign in instructions')); ?>
		<div class='italic'>
			The instructions will provide the user with a link to sign in and set their own password
		</div>

		<? if(!empty($invited)) { ?>
		<div class='alert alert-info'>
			This user was sent a signup email on <?= $invited ?>
		</div>
		<? } else { ?>
		<div class='alert alert-warning'>
			This user has not been sent a signup email with instructions for sign in.
		</div>
		<? } ?>

		<? if(empty($this->Form->data['User']['password'])) { # Clear text for admins (user account page is starred) ?>
		<?= $this->Form->input('password',array('type'=>'text','value'=>'','required'=>false,'label'=>'Set password', 'data-bv-stringLength'=>'true','data-bv-stringLength-min'=>8)); ?>
		<div class='alert alert-warning'>
			This user has never signed in and does not currently have a password. 
		</div>
		<? } else { # SET ?>
			<?= $this->Form->input('password',array('required'=>false,'type'=>'text','value'=>'','label'=>'Change password (optional)','tip'=>'Leave blank to keep existing password', 'data-bv-stringLength'=>'true','data-bv-stringLength-min'=>8)); ?>

		<? } ?>

		<? } ?>

		<? if(empty($id)) { ?>
		<div class='alert alert-info'>An email will be sent to the user with instructions for signing in and setting a password</div>
		<? } ?>

		<?= $this->Form->save(!$id?'Create Account':'Update Account',array('div'=>false,'class'=>'block right','cancel'=>false)); ?>
		<?#= $this->Html->blink("back", "Cancel", array('action'=>'index'),array('short'=>false,'class'=>'block left')); ?>
		<? if(!empty($id)) { ?>
			<?= $this->Html->delete("Delete", array('action'=>'delete',$this->data['User']['id']), array('confirm'=>"Are you sure you want to delete this account? ({$this->data['User']['email']})")); ?>
		<? } ?>
	<?= $this->Form->end(); ?>
	<div class='clear'></div>
	<script>
	</script>
</div>
<br/>
