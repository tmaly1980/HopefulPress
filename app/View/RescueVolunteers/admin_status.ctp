<div class='form'>
<?= $this->Form->create("RescueVolunteer",array('class'=>'')) ?>
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
	<div class='row'>
		<? if(!empty($this->request->data['Volunteer']['id'])) { ?>
		<div class='col-md-6'>
			<?= $this->Html->delete("Delete volunteer", array('action'=>'delete', $this->request->data['Volunteer']['id']),array('confirm'=>'Are you sure you want to remove this volunteer? Once it is deleted it cannot be recovered')); ?>
		</div>
		<? } ?>
		<?= $this->Form->save("Update",array('div'=>'col-md-6 right_align','cancel_js'=>"$.dialogclose();")); ?>
	</div>
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
