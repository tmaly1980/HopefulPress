	<div class="paddingtop5">
		<div class="">
			<?= $this->Html->link($event['Event']['title'], array('controller'=>'events','action'=>'view',$event["Event"]['idurl'],'project_id'=>$event['Event']['project_id']), array('class'=>"title")); ?>
		</div>
		<div class="">
			<?= $this->Date->mondy($event["Event"]['start_date']); ?><? if(!empty($event['Event']['end_date']) && !$this->Date->sameday($event["Event"]['start_date'], $event['Event']['end_date'])) { ?>
				&ndash; <?= $this->Date->mondy($event["Event"]['end_date']); ?><? } ?>,
			<? if(!empty($event['Event']['start_time'])) { ?>
				<?= $this->Date->time_12hm($event["Event"]['start_time']); ?>
				<? if(!empty($event['Event']['end_time']) && !$this->Date->sametime($event["Event"]['start_time'], $event['Event']['end_time'])) { ?>
					&ndash; <?= $this->Date->time_12hm($event["Event"]['end_time']); ?>
				<? } ?>
			<? } ?>
		</div>
		<div>
			<? if(!empty($event['EventLocation']['name'])) { ?>
			<div class="bold">
				<?= $event['EventLocation']['name'] ?>
			</div>
			<? } ?>
		</div>
		<? if(!empty($event['Event']['page_photo_id']) && $this->Admin->design("update_photos")) { ?>
			<?= $this->element("../PagePhotos/thumb", array('id'=>$event["Event"]['page_photo_id'],'wh'=>'225x150','href'=>array('plugin'=>null,'controller'=>'events','action'=>'view',$event['Event']['idurl']))); ?>
		<? } ?>

		<p class='small'>
			<?= $this->WPHtml->summary(!empty($event['Event']['summary']) ? $event["Event"]['summary'] : $event['Event']['details']); ?>
		</p>
	</div>
	<div class='clear'></div>

