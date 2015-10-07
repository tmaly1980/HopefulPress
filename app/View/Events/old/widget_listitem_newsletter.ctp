<div style='padding-top: 5px;'>
	<? if(!empty($event['Event']['page_photo_id']) && $this->Admin->design("update_photos")) { ?>
		<?= $this->element("../PagePhotos/thumb_newsletter", array('id'=>$event["Event"]['page_photo_id'])); ?>
	<? } ?>
	<div class='small' style='float: left; width: 150px; margin-right: 15px;'>
		<?= $this->Date->mondy($event["Event"]['start_date']); ?><? if(!empty($event['Event']['end_date']) && !$this->Date->sameday($event["Event"]['start_date'], $event['Event']['end_date'])) { ?>
			&ndash;<br/><?= $this->Date->mondy($event["Event"]['end_date']); ?><? } ?>
		<? if(!empty($event['Event']['start_time'])) { ?>
			<br/>
			<?= $this->Date->time_12hm($event["Event"]['start_time']); ?>
			<? if(!empty($event['Event']['end_time']) && !$this->Date->sametime($event["Event"]['start_time'], $event['Event']['end_time'])) { ?>
				&ndash; <?= $this->Date->time_12hm($event["Event"]['end_time']); ?>
			<? } ?>
		<? } ?>
	</div>
	<div style='overflow: hidden;'>
		<?= $this->Html->link($event['Event']['title'], array('controller'=>'events','action'=>'view',$event["Event"]['idurl']), array('class'=>"title")); ?>
		<? if(!empty($event['EventLocation']['name'])) { ?>
		<div style='font-weight: bold; padding: 5px 0px 5px 0px;'>
			<?= $event['EventLocation']['name'] ?>
		</div>
		<? } ?>
		<p class='small'>
			<?= !empty($event['Event']['summary']) ? $event['Event']['summary'] : $this->Text->truncate(strip_tags($event['Event']['details'])); ?>
		</p>
	</div>
</div>


