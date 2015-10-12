<? #$this->set("share", true); ?>
<? $this->start("title_controls"); ?>
	<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->add("Add Event", array('user'=>1,'action'=>'add'), array()); ?>
	<? } ?>
	<?= $this->element("../Events/list_toggler"); ?>
<? $this->end(); ?>
<?# $this->set('crumbs', true); ?>
<? #$project_title = $this->Admin->project('title'); ?>
<? $this->assign("page_title", "Events".(!empty($project_title)? " For $project_title":"")); ?>
<div class="events index fontify <?#= $this->Admin->fontsize('default'); ?>">

	<h3>Upcoming Events</h3>
	<? if(!empty($upcoming_events)) { ?>
		<?= $this->element("../Events/list", array('events'=>$upcoming_events)); ?>
	<? } else { ?>
		<div class='nodata'>There are no upcoming events</div>
	<? } ?>

	<div class='clear'></div>

	<div class='margintop50'>
	<h3>Past Events</h3>
	<? if(empty($previous_events)) { ?>
		<div class='nodata'>There are no past events</div>
	<? } else { ?>
		<?= $this->element("../Events/list", array('events'=>$previous_events)); ?>
		<?= $this->element("pager"); ?>
	<? } ?>
	</div>
</div>
