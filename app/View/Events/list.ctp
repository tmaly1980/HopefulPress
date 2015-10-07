	<? foreach($events as $event) { ?>
	<div class="block">
		<div class="left width150 marginright15">
			<?= $this->Time->mondy($event["Event"]['start_date']); ?>
			<? if(!empty($event['Event']['start_time'])) { ?>
				@ <?= $this->Time->time_12hm($event["Event"]['start_time']); ?>
			<? } ?>
			<? if(!empty($event['Event']['end_date']) && !$this->Time->sameday($event["Event"]['start_date'], $event['Event']['end_date'])) { ?>
				&ndash; <br/><?= $this->Time->mondy($event["Event"]['end_date']); ?>

				<? if(!empty($event['Event']['end_time']) && !$this->Time->sametime($event["Event"]['start_time'], $event['Event']['end_time'])) { ?>
					@ <?= $this->Time->time_12hm($event["Event"]['end_time']); ?>
				<? } ?>
			<? } ?>
		</div>
		<div class="left wrap">
			<?= $this->Html->link($event['Event']['title'], array('action'=>'view',$event["Event"]['idurl']), array('class'=>"")); ?>
			<? if(!empty($event['EventLocation']['name'])) { ?>
			<div class="bold">
				<?= $event['EventLocation']['name'] ?>
			</div>
			<? } ?>
			<p>
				<?= !empty($event["Event"]['summary']) ? $event["Event"]['summary'] : "&nbsp;"; ?>
			</p>
		</div>
		<div class='clear'></div>
	</div>
	<? } ?>
