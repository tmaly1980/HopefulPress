	<? if($this->Html->can_edit()) { ?>
			<?= $this->Form->input("{$this->Form->defaultModel}.status",array('id'=>'Status','default'=>'Active','options'=>$statuses)); ?>
	<div class='alert alert-info'>
		<b>Applied</b> have recent submitted via your website but do not yet have access to contribute content to your website<br/>
		<b>Active</b> can have user accounts to contribute content to your website<br/>
		<b>Inactive</b> no longer can sign in or contribute content
	</div>
	<div class='row' id="AccountInfo" style="<?= !isset($this->request->data[$this->Form->defaultModel]['status']) || $this->request->data[$this->Form->defaultModel]['status'] != 'Active' ? "display:none;":"" ?>">
	<?= $this->Form->input("disabled", array('div'=>'col-md-6','id'=>'AccountDisabled','label'=>'User Account','default'=>1,'options'=>array(0=>'Enabled',1=>'Disabled'))); ?>
	<div class='col-md-6'>
	<?= $this->Form->input("invite",array('id'=>'InviteEmail','label'=>'Send account sign in email','type'=>'checkbox')); ?>
		<div class='alert alert-info'>
		If checked, they will receive an email with instructions for signing in and contributing content to the website.
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
		$('#AccountDisabled').val(0); // Force disabled.
	}
});
$('#InviteEmail').check(function() {
	if($(this).val())
	{
		$('#AccountDisabled').val(0);
	}

});
</script>
