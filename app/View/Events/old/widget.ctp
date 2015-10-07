<? $pid = $this->Admin->project('id'); ?>

<div class="events index widget">
<? if(!empty($upcomingEvents) || !empty($in_admin)) { ?>
	<?= $this->element("../Events/widget_items", array('events'=>$upcomingEvents,'title'=>"Upcoming Events",'add'=>true)); ?>
<? } ?>

<? if(!empty($recentEvents)) { ?>
<?= $this->element("../Events/widget_items", array('events'=>$recentEvents,'title'=>"Recent Events")); ?>
<? } ?>

<? if(!empty($upcomingEvents) || !empty($recentEvents)) { ?>
<div align='right' class='padding10'>
	<?= $this->Html->link("More events...", array('controller'=>'events','action'=>'index','project_id'=>$pid), array('class'=>'small more')); ?>
</div>
<? } ?>
</div>
