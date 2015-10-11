<table class='table'>
<tr>
	<th>Name</th>
	<th>Address</th>
	<th>Phone</th>
	<th>Email</th>
</tr>
<? foreach($fosters as $foster) { 
?>
<tr class=''>
	<td><?= $this->Html->link(
		$foster['Foster']['full_name'],
		array('admin'=>1,'action'=>'view',$foster['Foster']['id'])
		); ?>
		<? if(!empty($foster['Foster']['password']) || !empty($foster['Foster']['invite'])) { ?>
			<? if(empty($foster['Foster']['disabled'])) { ?>
			<div class='bold green'>User account enabled</div>
			<? } else { ?>
			<div class='bold red'>User account disabled</div>
			<? } ?>
		<? } ?>
	</td>
	<td class='maxwidth200'>
		<?= join("<br/>", array(
			$foster['Foster']['address'], 
			$foster['Foster']['city'], 
			$foster['Foster']['state'], 
			$foster['Foster']['zip_code']
			));
		?>
	</td>
	<td>
		<?= !empty($foster['Foster']['home_phone']) ? "h: ".$foster['Foster']['home_phone']."<br/>" : "" ?>
		<?= !empty($foster['Foster']['work_phone']) ? "w: ".$foster['Foster']['work_phone']."<br/>":"" ?>
		<?= !empty($foster['Foster']['cell_phone']) ? "c: ".$foster['Foster']['cell_phone']."<br/>":"" ?>
		<?= !empty($foster['Foster']['best_time_to_call']) ? "best time: ".$foster['Foster']['best_time_to_call']."<br/>":"" ?>
	</td>
	<td>
		<?= empty($foster['Foster']['email']) ? null :
			$this->Html->link($this->Html->g("envelope")." ".$foster['Foster']['email'],
				"mailto:".$foster['Foster']['email'],array('class'=>'')); ?>
	</td>
</tr>
<? } ?>
</table>

