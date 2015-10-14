<table class='table'>
<tr>
	<th>Date</th>
	<th>Name</th>
	<th>Address</th>
	<th>Phone</th>
	<th>Email</th>
</tr>
<? foreach($adoptions as $adoption) { 
?>
<tr class=''>
	<td><?= $this->Time->mond($adoption['Adoption']['created']);?></td>
	<td><?= $this->Html->link(
		!empty($adoption['Adoption']['full_name']) ?  $adoption['Adoption']['full_name'] : "<i>No name provided</i>",
		array('user'=>1,'controller'=>'adoptions','action'=>'view',$adoption['Adoption']['id'])
		); ?>
	</td>
	<td class='maxwidth200'>
		<?= join("<br/>", array(
			$adoption['Adoption']['address'], 
			$adoption['Adoption']['city'], 
			$adoption['Adoption']['state'], 
			$adoption['Adoption']['zip_code']
			));
		?>
	</td>
	<td>
		<?= !empty($adoption['Adoption']['home_phone']) ? "h: ".$adoption['Adoption']['home_phone']."<br/>" : "" ?>
		<?= !empty($adoption['Adoption']['work_phone']) ? "w: ".$adoption['Adoption']['work_phone']."<br/>":"" ?>
		<?= !empty($adoption['Adoption']['cell_phone']) ? "c: ".$adoption['Adoption']['cell_phone']."<br/>":"" ?>
		<?= !empty($adoption['Adoption']['best_time_to_call']) ? "best time: ".$adoption['Adoption']['best_time_to_call']."<br/>":"" ?>
	</td>
	<td>
		<?= empty($adoption['Adoption']['email']) ? null :
			$this->Html->link($this->Html->g("envelope")." ".$adoption['Adoption']['email'],
				"mailto:".$adoption['Adoption']['email'],array('class'=>'')); ?>
	</td>
</tr>
<? } ?>
</table>

