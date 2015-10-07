<?
$skip_keys = array('id','site_id','created','modified','status','adoptable_id','full_name');
?>
<table class='table table-responsive'>
<tr>
	<th>Status: </th>
	<td>
		<?= $adoption['Adoption']['status'] ?>
		<? if($adoption['Adoption']['status'] == 'Accepted') { ?>
			<!-- xxx todo -->
		<? } ?>
	</td>
</tr>
<? if(!empty($adoption['Adoptable']['id'])) { # They can re-assign afterward! Set status/adoptable ?>
<tr>
	<th>Specific Adoptable: </th>
	<td>
		<?= $this->Html->link($adoption['Adoptable']['select_label'],array('controller'=>'adoptables','action'=>'view',$adoption['Adoptable']['id'])) ?> 
		<? /* LATER LOGIC TO STREAMINE... * if($adoption['Adoption']['status'] == 'Accepted') { ?>
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
<? foreach($adoption['Adoption'] as $k=>$v) { if(in_array($k,$skip_keys)) { continue; } ?>
	<?= $this->element("Rescue.form_details",array('k'=>$k,'v'=>$v)); ?>
<? } ?>
</table>

