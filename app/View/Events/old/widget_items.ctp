<? $pid = $this->Admin->project('id'); ?>
	<h3>
		<? if(!empty($add)) { ?>
			<?#= $this->Html->add_link(null,'events'); ?>
		<? } ?>
		<?= !empty($title) ? $title : "Events" ?></h3>
<div class='items'>
	<div class='clear'></div>

<? if(empty($events)) { ?>
	<div class='nodata'>
		There are no events yet.
		<? if(!empty($in_admin) && $this->Admin->access()) { ?>
			<?= $this->Html->link("Add an event", array('controller'=>'events','action'=>'add','project_id'=>$pid), array('class'=>'color green')); ?>
		<? } ?>
	</div>
<? } else { ?>
	<? foreach($events as $event) { ?>
		<?= $this->element("../Events/widget_item", array('event'=>$event)); ?>
	<? } ?>
<? } ?>

</div>
