<? $this->set("admin_back", true); ?>
<? $this->assign("title", "Events on ".$this->Date->monthdyear("$year-$month-$day")); ?>
<div class="events index">
	<?#= $this->Share->share(); ?>
	<div align='right'>
		<?= $this->Html->link("View Event Calendar", array('action'=>'calendar',$year,$month), array()); ?> |
		<?= $this->Html->link("View Event List", array('action'=>'index'), array()); ?>
	</div>

	<? if(!empty($events)) { ?>
		<?= $this->element("../Events/list", array('events'=>$events)); ?>
	<? } ?>

</div>
