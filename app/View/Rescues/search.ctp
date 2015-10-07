<? $this->assign("page_title", "Local Rescues"); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->me() && ($myrescue = $this->Html->user("Rescue.hostname"))) { ?>
	<?= $this->Html->link("My rescue", array('action'=>'view',$myrescue),array('class'=>'btn btn-primary')); ?>
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
