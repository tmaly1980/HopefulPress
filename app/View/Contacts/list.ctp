<? if(!empty($contacts)) { ?>
<div class='col-md-9'>
<table class='table contactlist'>
<? foreach($contacts as $contact) { ?>
<tbody class='Contact_<?= $contact['Contact']['id'] ?>'>
<tr>
	<td rowspan=2>
		<b><?= $contact['Contact']['name'] ?><? if(!empty($contact['Contact']['title'])) { ?>, <i><?= $contact['Contact']['title'] ?></i><? } ?></b>
		&nbsp;
		<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->blink("edit", null, array('controller'=>'contacts','action'=>'edit',$contact['Contact']['id']),array('class'=>'btn-xs btn-info')); ?>
		<?= $this->Html->blink("trash", null, array('controller'=>'contacts','action'=>'delete',$contact['Contact']['id']),array('confirm'=>'Are you sure you want to remove this contact?','class'=>'btn-xs btn-danger')); ?>
		<? } ?>
		<div class='clear'></div>
	</td>
	<td>
		<?= $contact['Contact']['phone'] ?><br/>
		<?= $contact['Contact']['alternate_phone'] ?>
	</td>
	<td>
		<?= $this->Text->autoLinkEmails($contact['Contact']['email']); ?>
	</td>
</tr>
<tr>
	<td colspan=3>
		<?= nl2br($contact['Contact']['details']) ?><br/>
	</td>
</tr>
</tbody>
<? } ?>
</table>
</div>
<? } ?>
