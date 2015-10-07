<table class='table'>
<tr>
	<th>Name</th>
	<th>Address</th>
	<th>Phone</th>
	<th>Email</th>
</tr>
<? foreach($volunteers as $volunteer) { 
?>
<tr class=''>
	<td><?= $this->Html->link(
		$volunteer['Volunteer']['full_name'],
		array('admin'=>1,'action'=>'view',$volunteer['Volunteer']['id'])
		); ?>
		<? if(!empty($volunteer['Volunteer']['password']) || !empty($volunteer['Volunteer']['invite'])) { ?>
			<? if(empty($volunteer['Volunteer']['disabled'])) { ?>
			<div class='bold green'>User account enabled</div>
			<? } else { ?>
			<div class='bold red'>User account disabled</div>
			<? } ?>
		<? } ?>
	</td>
	<td class='maxwidth200'>
		<?= join("<br/>", array(
			$volunteer['Volunteer']['address'], 
			$volunteer['Volunteer']['city'], 
			$volunteer['Volunteer']['state'], 
			$volunteer['Volunteer']['zip_code']
			));
		?>
	</td>
	<td>
		<?= !empty($volunteer['Volunteer']['home_phone']) ? "h: ".$volunteer['Volunteer']['home_phone']."<br/>" : "" ?>
		<?= !empty($volunteer['Volunteer']['work_phone']) ? "w: ".$volunteer['Volunteer']['work_phone']."<br/>":"" ?>
		<?= !empty($volunteer['Volunteer']['cell_phone']) ? "c: ".$volunteer['Volunteer']['cell_phone']."<br/>":"" ?>
		<?= !empty($volunteer['Volunteer']['best_time_to_call']) ? "best time: ".$volunteer['Volunteer']['best_time_to_call']."<br/>":"" ?>
	</td>
	<td>
		<?= empty($volunteer['Volunteer']['email']) ? null :
			$this->Html->link($this->Html->g("envelope")." ".$volunteer['Volunteer']['email'],
				"mailto:".$volunteer['Volunteer']['email'],array('class'=>'')); ?>
	</td>
</tr>
<? } ?>
</table>

