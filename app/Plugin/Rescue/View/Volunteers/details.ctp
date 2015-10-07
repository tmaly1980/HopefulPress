<?
$skip_keys = array('id','site_id','password','created','modified','full_name','status');
?>
<table class='table table-responsive'>
<tr>
	<th>Status: </th>
	<td>
		<?= $volunteer['Volunteer']['status'] ?>
		<? if($volunteer['Volunteer']['status'] == 'Active') { ?>
			<? if(!empty($volunteer['Volunteer']['password'])) { ?>
				<div class='bold green'>User account is active</div>
			<? } else if(!empty($volunteer['Volunteer']['invite'])) { ?>
				<div class='bold green'>User has been sent an invitation email but has not activated their account yet</div>
			<? } else { ?>
				<div class='bold'>User account has not been created</div>
			<? } ?>
		<? } ?>
	</td>
</tr>
<? foreach($volunteer['Volunteer'] as $k=>$v) { if(in_array($k,$skip_keys)) { continue; } ?>
	<?= $this->element("Rescue.form_details",array('k'=>$k,'v'=>$v)); ?>
<? } ?>
</table>

