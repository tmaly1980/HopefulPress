<table class='table'>
<tr>
	<th>Date</th>
	<th>Name</th>
	<th>Address</th>
	<th>Phone</th>
	<th>Email</th>
</tr>
<? foreach($adoptions as $adopter) { 
?>
<tr class=''>
	<td><?= $this->Time->mond($adopter['Adopter']['created']);?></td>
	<td>
		<? if(!empty($adoptable_id)) { ?>
			<?= $adopter['Adopter']['full_name'] ?>
			<br/>
			<?= $this->Html->add("Select", array('controller'=>'adoptables','action'=>'select_adopters',$adoptable_id,$adopter['Adopter']['id'])); ?>
		<? } else { ?>
			<?= $this->Html->link(
				!empty($adopter['Adopter']['full_name']) ?  $adopter['Adopter']['full_name'] : "<i>No name provided</i>",
				array('user'=>1,'controller'=>'adopters','action'=>'view',$adopter['Adopter']['id'])
			); ?>
		<? } ?>
	</td>
	<td class='maxwidth200'>
		<?= join("<br/>", array(
			$adopter['Adopter']['address'], 
			$adopter['Adopter']['city'], 
			$adopter['Adopter']['state'], 
			$adopter['Adopter']['zip_code']
			));
		?>
	</td>
	<td>
		<?= !empty($adopter['Adopter']['home_phone']) ? "h: ".$adopter['Adopter']['home_phone']."<br/>" : "" ?>
		<?= !empty($adopter['Adopter']['work_phone']) ? "w: ".$adopter['Adopter']['work_phone']."<br/>":"" ?>
		<?= !empty($adopter['Adopter']['cell_phone']) ? "c: ".$adopter['Adopter']['cell_phone']."<br/>":"" ?>
		<?= !empty($adopter['Adopter']['best_time_to_call']) ? "best time: ".$adopter['Adopter']['best_time_to_call']."<br/>":"" ?>
	</td>
	<td>
		<?= empty($adopter['Adopter']['email']) ? null :
			$this->Html->link($this->Html->g("envelope")." ".$adopter['Adopter']['email'],
				"mailto:".$adopter['Adopter']['email'],array('class'=>'')); ?>
	</td>
</tr>
<? } ?>
</table>

