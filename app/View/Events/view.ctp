<? $id = $event['Event']['id']; ?>
<? $this->assign("page_title", $event['Event']['title']); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->back("All events", array('action'=>'index')); ?>
	<? if($this->Html->can_edit($event['Event'])) { ?>
		<?= $this->Html->edit("Edit event", array('user'=>1,'action'=>'edit',$id)); ?>
	<? } ?>
<? $this->end("admin_controls"); ?>
<? $this->set('crumbs', true); ?>
<? $this->set('share', true); ?>
<div class="events view fontify <?#= $this->Admin->fontsize('default'); ?>">

<div class='block'>
	<?= $this->element("PagePhotos.view"); ?>
	
	<div class='wrap'>
		<? if(!empty($event["Event"]['summary'])) { ?>
		<div class="paddingbottom10">
			<div class="bold marginbottom10">What:</div>
			<div class="wrap indent">
				<?= nl2br($event['Event']['summary']); ?>
			</div>
		</div>
		<? } ?>
		
		<? if(!empty($event["Event"]['start_date'])) { ?>
		<div class="paddingbottom10">
			<div class="bold marginbottom10">When:</div>
			<div class="wrap indent">
					<?= $this->Time->mondy($event["Event"]['start_date']); ?>
					<? if(!empty($event['Event']['start_time'])) { ?>
						@ <?= $this->Time->time_12hm($event["Event"]['start_time']); ?>
					<? } ?>
					<? if(!empty($event['Event']['end_date']) && !$this->Time->sameday($event["Event"]['start_date'], $event['Event']['end_date'])) { ?>
						&ndash; <br/>
						<?= $this->Time->mondy($event["Event"]['end_date']); ?>
					<? } ?>
						<? if(!empty($event['Event']['end_time']) && !$this->Time->sametime($event["Event"]['start_time'], $event['Event']['end_time'])) { ?>
							@ <?= $this->Time->time_12hm($event["Event"]['end_time']); ?>
						<? } ?>
			</div>
		</div>
		<? } ?>
		
		<? if(!empty($event["EventLocation"]['name'])) { ?>
		<div class="">
			<div class="bold marginbottom10">Where:</div>
			<div class="wrap indent">
				<?= $this->element("../EventLocations/details", array('eventLocation'=>$event)); ?>
			</div>
		</div>
		<? } ?>
		
		<? if(!empty($event["Event"]['details'])) { ?>
		<div class="paddingbottom10 minheight100">
			<div class="bold marginbottom10">Details:</div>
			<div class="wrap indent">
				<?= ($event['Event']['details']); ?>
			</div>
		</div>
		<? } ?>
		<div class='clear'></div>
		
		<? if(!empty($event["EventContact"]['name'])) { ?>
		<div class="paddingbottom10">
			<div class="bold marginbottom10">Contact for further information:</div>
			<div class="wrap indent">
				<?= $this->element("../EventContacts/details", array('eventContact'=>$event)); ?>
			</div>
		</div>
		<? } ?>
	
	</div>
	
	<div class="clear"></div>

</div>


</div>
