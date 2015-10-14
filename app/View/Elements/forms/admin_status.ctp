	<? if($this->Html->can_edit()) { ?>
	<div class='row'>
		<div class='col-md-6'>
			<?= $this->Form->input("status",array('id'=>'Status','default'=>'Active Online','options'=>$statuses)); ?>

			<div id="AccountInfo" style="<?= !isset($this->request->data[$this->Form->defaultModel]['status']) || $this->request->data[$this->Form->defaultModel]['status'] != 'Active' ? "display:none;":"" ?>">
				<? if($this->Form->defaultModel != 'RescueFoster') { # Fosters cant have admin access ?>
				<?= $this->Form->input("admin", array('id'=>'AccountAdmin','label'=>'Administrator access','default'=>0,'type'=>'checkbox')); #options'=>array(0=>'Enabled',1=>'Disabled'))); ?>
				<div class='alert alert-info'>
					<b>Administrators</b> can update any part of your site (include manage other volunteers, fosters and admins) except your account status and billing information.
				</div>
				<? } ?>
				<?= $this->Form->input("invite",array('id'=>'InviteEmail','label'=>'Send account sign in email','type'=>'checkbox')); ?>
				<div class='alert alert-info'>
					If checked, they will receive an email with instructions for signing in and contributing content to the website.
				</div>
			</div>
		</div>
		<div class='col-md-6'>
			<div class='alert alert-info'>
				<b>Active</b> will have user accounts to contribute content to your website, including news, events, photos, resources and updating adoptables<br/>
				<b>Active Offline</b> will be listed in your Volunteer list but will not be able to sign in and contribute content<br/>
				<b>Applied</b> have recent submitted their details via your website<br/>
				<b>Inactive</b> no longer work for your organization<br/>
				<b>Ignored</b> are not considered
			</div>
		</div>
	</div>
	<? } ?>

<script>
$('#Status').change(function() {
	var status  = $(this).val();
	if(status == 'Active') {
		$('#AccountInfo').show();
	} else {
		$('#AccountInfo').hide();
	}
});
</script>
