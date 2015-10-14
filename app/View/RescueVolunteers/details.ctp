<?
$volunteer = $rescueVolunteer;
$skip_keys = array('id','password','created','modified','full_name','status');
?>
<table class='table table-responsive'>
<tr>
	<th>Status: </th>
	<td>
		<?= $volunteer['RescueVolunteer']['status'] ?>
		<? if($volunteer['RescueVolunteer']['status'] == 'Active') { ?>
			<? if(!empty($volunteer['User']['password'])) { ?>
				<div class='bold green'>User account is active</div>
			<? } else if(!empty($volunteer['User']['invite'])) { ?>
				<div class='bold green'>User has been sent an invitation email but has not activated their account yet</div>
			<? } else  if (empty($volunteer['User']['id'])) { ?>
				<div class='bold'>User account has not been created</div>
			<? } else { # New user. ?>
				<div class='bold'>User has not been sent an invitation email yet</div>
			<? } ?>
		<? } ?>
	</td>
</tr>
<? foreach($volunteer['Volunteer'] as $k=>$v) { if(in_array($k,$skip_keys) || preg_match("/_id$/",$k)) { continue; } ?>
	<?= $this->element("form_details",array('k'=>$k,'v'=>$v)); ?>
<? } ?>
<? foreach($volunteer['RescueVolunteer'] as $k=>$v) { if(in_array($k,$skip_keys) || preg_match("/_id$/",$k)) { continue; } ?>
	<?= $this->element("form_details",array('k'=>$k,'v'=>$v)); ?>
<? } ?>
</table>

