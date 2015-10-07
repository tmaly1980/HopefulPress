<div class='form'>
<?= $this->Form->create("Volunteer",array('class'=>'')) ?>
	<?= $this->Form->hidden("id"); ?>
	<?= $this->Form->input("status",array('id'=>'VolunteerStatus','options'=>array_combine($statuses,$statuses))); ?>
	<div class='alert alert-info'>
		<b>Applied</b> volunteers are recent submissions via your website but do not yet have access to contribute content to your website<br/>
		<b>Active</b> volunteers can have user accounts to contribute content to your website<br/>
		<b>Inactive</b> volunteers will no longer be able to sign in or contribute content
	</div>
	<div id="InviteCheckbox" style="<?= $this->request->data['Volunteer']['status'] != 'Active' ? "display:none;":"" ?>">
	<?= $this->Form->input("invite",array('label'=>'Send account sign in email','type'=>'checkbox')); ?>
	</div>
	<?= $this->Form->save("Update",array('cancel_js'=>"$.dialogclose();")); ?>
<?= $this->Form->end(); ?>
</div>
<script>
$('#VolunteerStatus').change(function() {
	var status  = $(this).val();
	if(status == 'Active') {
		$('#InviteCheckbox').show();
	} else {
		$('#InviteCheckbox').hide();
	}
});
</script>
