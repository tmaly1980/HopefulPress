<? $this->assign("page_title", "Donation History"); ?>
<? if(empty($nav['donationsEnabled'])) { ?>
	<?= $this->element("../Donations/setup"); ?>
<? } else { ?>
<div class='index'>
	<?= $this->element("Donation.enabled_message"); ?>
<? if(empty($donations)) { ?>
	<div class='nodata'>
		No donations have been contributed yet.
	</div>
<? } else { ?>
	<table class='table'>
	<tr>
		<th><?= $this->Paginator->sort("created",'Date'); ?></th>
		<th><?= $this->Paginator->sort("name"); ?></th>
		<th><?= $this->Paginator->sort("email"); ?></th>
		<th><?= $this->Paginator->sort("adoptable_id","Sponsorship"); ?></th>
		<th><?= $this->Paginator->sort("recurring"); ?></th>
		<th><?= $this->Paginator->sort("amount"); ?></th>
	</tr>
	<? foreach($donations as $donation) { ?>
	<tr id="Donation<?=$donation['Donation']['id']?>">
		<td><?= $this->Time->mondyhm($donation['Donation']['created']); ?></td>
		<td><?= $donation['Donation']['name']; ?></td>
		<td><?= $this->Html->link($donation['Donation']['email'],"mailto:".$donation['Donation']['email']); ?></td>
		<td><?= !empty($donation['Adoptable']['name'])  ? $this->Html->link($donation['Adoptable']['name'], array('plugin'=>'rescue','controller'=>'adoptables','action'=>'view',$donation['Adoptable']['id'])) : "General"; ?></td>
		<td><?= !empty($donation['Donation']['recurring']) ? "Monthly" : "One-time"; ?></td>
		<td><?= sprintf("\$%.02f", $donation['Donation']['amount']); ?></td>
	</tr>
	<? if(!empty($donation['Donation']['note'])) { ?>
	<tr>
		<td colspan=6>
			<b>Note:</b> <?= $donation['Donation']['note']; ?>
		</td>
	</tr>
	<? } ?>
	<? } ?>
	</table>
<? } ?>
</div>

<?= $this->element("pager"); ?>
<? } ?>
