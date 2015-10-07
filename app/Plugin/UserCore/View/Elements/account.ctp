<script>j.modaltitle('Update Your Account');</script>
<? $id = $this->Model->id(); ?>

<div class="users form width500">
<?php echo $this->Form->create(null, array('json'=>1)); # Needs to validate without closing window. ?>
<table width="100%">
<tr><td width="50%">
	<?= $this->Form->input("id"); ?>
	<?= $this->Form->input("email",array('label'=>'Your Email','class'=>'width90p')); ?>

	<?= $this->Form->input("first", array('label'=>'First Name','class'=>'width90p')); ?>
	<?= $this->Form->input("last", array('label'=>'Last Name','class'=>'width90p')); ?>

	<?= $this->Form->input("share_contact_information", array('id'=>'ShareContactInformation')); ?>
</td><td>
	<?= $this->Form->input("password", array('label'=>'Change Your Password (optional)','value'=>'','type'=>'password','note'=>'Leave blank to keep your existing password','class'=>'width90p','required'=>false)); ?>
	<?= $this->Form->input("password2", array('label'=>'Verify Your Password','type'=>'password','value'=>'','class'=>'width90p','required'=>false)); ?>
</td></tr>

<? if(!empty($memberPage['MemberPage']['contact_list_enabled'])) { ?>
<tr id='ContactInfo' style="<?= $this->Model->field("share_contact_information") ? "" : "display: none;" ?>">
<td width="50%">
	<?= $this->Form->input("home_phone", array('class'=>'width90p')); ?>
	<?= $this->Form->input("cell_phone", array('class'=>'width90p')); ?>
</td><td>
	<?= $this->Form->input("work_phone", array('class'=>'width90p')); ?>
	<?= $this->Form->input("address", array('class'=>'width90p')); ?>
</td></tr>
		<script>
		j('#ShareContactInformation').click(function() {
			if(j(this).is(":checked")) { 
				j('#ContactInfo').show();
			} else { 
				j('#ContactInfo').hide();
			}
			j('#modal').modalcenter();
		});
		</script>
<? } ?>

</table>

	<?= $this->Form->save('Update Account', array('cancel_js'=>"j.modalclose();")); ?>

<?php echo $this->Form->end(); ?>
</div>
