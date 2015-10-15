<?
$skip_keys = array('id','site_id','created','modified','status','adoptable_id','full_name');
?>
<table class='table table-responsive'>
<tr>
	<th>Status: </th>
	<td>
		<?= $adopter['Adopter']['status'] ?>
		<? if($adopter['Adopter']['status'] == 'Approved') { ?>
			<!-- xxx todo -->
		<? } ?>
	</td>
</tr>
<? if(!empty($adopter['Adoptable']['id'])) { # They can re-assign afterward! Set status/adoptable ?>
<tr>
	<th>Specific Adoptable: </th>
	<td>
		<?= $this->Html->link($adopter['Adoptable']['select_label'],array('controller'=>'adoptables','action'=>'view',$adopter['Adoptable']['id'])) ?> 
		<? /* LATER LOGIC TO STREAMINE... * if($adopter['Adopter']['status'] == 'Accepted') { ?>
			<!-- xxx todo -->
			<!-- set adopted status button -->
			<!-- add success story link.... -->
		<? } else { ?>
			<div class='alert alert-info'>
			Please approve the adoption request in order to mark the specific adoptable as 'Adopted'.
			</div>
		<? } */ ?>
	</td>
</tr>
<? } ?>
<? foreach($adopter['Adopter'] as $k=>$v) { if(in_array($k,$skip_keys) || preg_match("/_id$/", $k)) { continue; } ?>
	<?= $this->element("form_details",array('k'=>$k,'v'=>$v)); ?>
<? } ?>
</table>

