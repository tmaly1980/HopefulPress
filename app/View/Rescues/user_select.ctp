<? $rescues = $this->Html->user("Rescues"); ?>
<? $this->assign("page_title", "Select a rescue to continue"); ?>
<div class='index'>
<table class='table table-striped'>
<? foreach($rescues as $rescue) { ?>
<tr>
	<td>
		<?= $this->Html->link($rescue['title'], array('action'=>'select',$rescue['hostname']),array('class'=>'btn btn-success')); ?>
	</td>
</tr>
<? } ?>
<tr>
	<td>
		<b>OR</b>
		<?= $this->Html->add("Add a new rescue",array('action'=>'add')); ?>
	</td>
</tr>
</table>
</div>
