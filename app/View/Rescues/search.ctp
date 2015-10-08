<? $this->assign("page_title", !empty($this->request->data) ? "Search Results" : "Local Rescues"); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->me() && ($myrescue = $this->Html->user("Rescue.hostname"))) { ?>
	<?= $this->Html->blink("fa-paw","My rescue", array('action'=>'view',$myrescue),array('class'=>'btn btn-primary')); ?>
<? } else { ?>
	<?= $this->Html->add("Add my rescue", array('rescuer'=>1,'action'=>'add')); ?>
<? } ?>
<? $this->end(); ?>
<div class='index'>
<? if(empty($rescues)) { ?>
<div class='nodata'>
	No rescues found.
</div>
<? } ?>
<table class='table table-striped'>
<? foreach($rescues as $rescue) { ?>
<tr>
	<td>
		<?= $this->Html->link($rescue['Rescue']['title'],array('controller'=>'rescues','action'=>'view','rescue'=>$rescue['Rescue']['hostname'])); ?>
	</td>
	<!--
	<td>
		Staff size (incl founder, fosters, volunteers)
	</td>
	-->
	<td>
		<?= join(", ", array($rescue['Rescue']['city'],$rescue['Rescue']['state'])); ?>
		<!-- distance??? -->
	</td>
	<td>
		<!-- restrictions -->
	</td>
	<td>
		<b><?= !empty($rescue['adoptableCount']) ? $rescue['adoptableCount'] : "No" ?> available adoptables</b>
		&nbsp;
		&nbsp;
		<? if(!empty($rescue['adoptableCount'])) { ?>
			<?= $this->Html->link("Browse",array('controller'=>'adoptables','action'=>'index','rescue'=>$rescue['Rescue']['hostname']),array('class'=>'btn btn-primary')); ?>
		<? } ?>
	</td>
</tr>
<? } ?>
</table>
</div>
