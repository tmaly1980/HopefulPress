	<? foreach($tickets as $ticket) { ?>
	<div class='row margintop15 lightgreybg'>
		<div class='col-md-2 center_align'>
			<?= $this->Time->smarttime($ticket['Ticket']['created']); ?>
			<? if(!empty($ticket['User']['id'])) { ?>
			<div>
				<?= $this->Html->image(!empty($ticket['User']['page_photo_id']) ? "/page_photos/page_photos/image/".$ticket['User']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
				<br/>
				<?= $ticket['User']['full_name']; ?>
			</div>
	 		<? } ?>
		</div>
		<div class='col-md-10'>
			<div class='medium bold marginbottom10'><?= $this->Html->link($ticket['Ticket']['title'],array('action'=>'view',$ticket['Ticket']['id'])); ?>

			<? if(!empty($ticket['Ticket']['confirmed'])) { ?>
				<span class='bg-success padding5'>RESOLVED - CONFIRMED</span>
			<? } else if (!empty($ticket['Ticket']['resolved'])) { ?>
				<span class='bg-warning padding5'>RESOLVED - NEEDS TESTING</span>
			<? } else if (!empty($ticket['Ticket']['deferred'])) { ?>
				<span class='bg-info padding5'>DEFERRED UNTIL <?= date("M j", strtotime($ticket['Ticket']['deferred_until'])); ?></span>
			<? } else if (!empty($ticket['Ticket']['estimated'])) { ?>
				<span class='bg-warning padding5'>PENDING</span>
			<? } else { ?>
				<span class='bg-warning padding5'>NEW</span>
			<? } ?>
			</div>
			<p>
				<?= $this->Text->truncate($ticket['Ticket']['description']); ?>
			</p>
			<? if(!empty($ticket['Ticket']['resolved'])) { ?>
			Resolved in <?= $this->Html->link($this->Time->timebetween($ticket['Ticket']['resolved'], $ticket['Ticket']['created']), "javascript:void(0)",array('title'=>$this->Time->mondyhm($ticket['Ticket']['resolved']))); ?>
			<? if(!empty($ticket['Ticket']['resolverer_user_id'])) { ?>
				by 
				<?= $this->Html->image(!empty($ticket['Tech']['page_photo_id']) ? "/page_photos/page_photos/image/".$ticket['Tech']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
				<?= $ticket['Tech']['full_name'] ?>
			<? } ?>
			<? } ?>
		</div>
	</div>
	<? } ?>

