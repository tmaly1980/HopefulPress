<div class="paddingtop5">
	<? if(!empty($event['Event']['page_photo_id']) && $this->Admin->design("update_photos")) { ?>
		<?= $this->element("../PagePhotos/thumb", array('id'=>$event["Event"]['page_photo_id'],'href'=>array('plugin'=>null,'controller'=>'events','action'=>'view',$event['Event']['idurl']))); ?>
	<? } ?>
	<div class="left width150 marginright15 small">
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
	<div class="wrap">
		<?= $this->Html->link($event['Event']['title'], array('controller'=>'events','action'=>'view',$event["Event"]['idurl']), array('class'=>"title")); ?>
		<? if(!empty($event['EventLocation']['name'])) { ?>
		<div class="bold paddingtop5 paddingbottom5">
			<?= $event['EventLocation']['name'] ?>
		</div>
		<? } ?>
		<p class='small'>
			<?= $this->WPHtml->summary($event['Event']['details']); ?>
		</p>
	</div>
</div>
<div class='clear'></div>


