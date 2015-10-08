<? if(empty($updates['upcomingEvents']) && !$this->Html->me() /*&& !$this->Html->can_edit()*/) { return; } ?>

<? $prefix = Configure::read("members_only") ? "members" : "rescuer"; ?>
<? $pid = Configure::read("project_id"); ?>
<? $project = Configure::read("project"); ?>

<div class='widget'>
<h3>
	<?= $this->Html->link("Upcoming Events", array('plugin'=>null,'controller'=>'events','action'=>'index')); ?>
</h3>
<? if(false && $this->Html->can_edit()) { ?>
<div class='alert-warning border2 dashed'>
	<?= $this->Html->add((!empty($updates['previousEvents']) && !empty($updates['upcomingEvents'])) ? "Add another event":"Add your first event", array($prefix=>1,'plugin'=>null,'controller'=>'events','action'=>'add','project_id'=>$pid),array('title'=>'Add Event','class'=>'controls btn-warning')); ?>
</div>
<? } ?>
<? if(empty($updates['upcomingEvents'])) { ?>
        <? if($this->Html->me()) { ?>
        <div class='dashed alert alert-info'>
                You have no upcoming events.
                <?= $this->Html->add("Add an event", array('rescuer'=>1,'controller'=>'events','action'=>'add')); ?>
        </div>
        <? } ?>
<? } else { ?>
	<div class=''>
	<? foreach($updates['upcomingEvents'] as $event) { ?>
		<div class='left paddingbottom10 maxwidth250'>
		<? if(!empty($event['Event']['page_photo_id'])) { ?>
			<?= $this->element("PagePhotos.thumb", array('id'=>$event['Event']['page_photo_id'],'href'=>array('controller'=>'events','action'=>'view',$event['Event']['idurl']))); ?>
		<? } ?>
		<div class=''>
			<?= $this->Time->mond($event['Event']['start_date']); ?>
			<? if(!empty($event['Event']['end_date']) && !$this->Time->sameday($event['Event']['start_date'], $event['Event']['end_date'])) { ?>
				&ndash;
				<?= $this->Time->mond($event['Event']['end_date']); ?>
			<? } ?>
		</div>
		<?= $this->Html->titlelink($event['Event']['title'], array('plugin'=>null,'controller'=>'events','action'=>'view',$event['Event']['idurl'])); ?>

		<? if(!empty($event['Event']['summary'])) { ?>
			<div><?= $event['Event']['summary'] ?></div>
			<!--
			<div align='right'>
				<?= $this->Html->link("read more...", array('plugin'=>null,'controller'=>'events','action'=>'view',$event['Event']['idurl']), array('class'=>'btn')); ?>
			</div>
			-->
		<? } ?>
		</div>
	<? } ?>
	<div class='clear'></div>
	<? //if(!empty($updates['upcomingEvents'])) { ?>
	<?= $this->Html->link("Previous events ".$this->Html->g("chevron-right"), array('plugin'=>null,'controller'=>"events"), array('class'=>'btn more right_align medium')); ?>
	<? //} ?>
	</div>
<? } ?>
</div>
