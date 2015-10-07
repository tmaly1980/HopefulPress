<? $this->assign("page_title", "Local Rescues"); ?>
<? if($this->Html->me()) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add a rescue", array('rescuer'=>1,'action'=>'add')); ?>
<? $this->end(); ?>
<? } ?>
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
	<td>
		Staff size (incl founder, fosters, volunteers)
	</td>
	<td>
		Location
	</td>
	<td>
		Specialization
	</td>
	<td>
		# active adoptables
		<?= $this->Html->link("Browse adoptables",array('controller'=>'adoptables','action'=>'index','rescue'=>$rescue['Rescue']['hostname'])); ?>
	</td>
</tr>
<? } ?>
</table>
</div>
