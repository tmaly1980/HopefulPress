<?= $this->element("Core.js/editor"); ?>
<? $id = $ticket['Ticket']['id'];  ?>
<? $this->assign("page_title", "Support Ticket"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All tickets",array('action'=>'index')); ?>
	<? if($this->Html->user("manager")) { ?>
		<?= $this->Html->delete("Remove ticket",array('manager'=>1,'action'=>'delete',$id),array('confirm'=>'Are you sure you want to remove this ticket?')); ?>
	<? } ?>
<? $this->end(); ?>

<div class='view'>


<? if($this->Html->user("manager") || $ticket['Ticket']['user_id'] == $this->Html->me()) { ?>
	<?= $this->Form->create("Ticket", array('url'=>array('user'=>1,'action'=>'status',$ticket['Ticket']['id']))); ?>
	<?= $this->Form->hidden("id",array('value'=>$ticket['Ticket']['id'])); ?>
	<?= $this->Form->hidden("TicketComment.0.ticket_id",array('value'=>$ticket['Ticket']['id'])); ?>
<? } ?>
<div class='right' align="right">
	Submitted <?= $this->Time->smarttime($ticket['Ticket']['created']); ?>
	<? if(!empty($ticket['User']['id'])) { ?>
	<div align='right'>
		<?= $ticket['User']['full_name']; ?>
		<?= $this->Html->image(!empty($ticket['User']['page_photo_id']) ? "/page_photos/page_photos/image/".$ticket['User']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
	</div>
	<? } ?>
	<br/>
	<? if(!empty($ticket['Tech']['id'])) { ?>
	<div>
		Assigned to <br/><?= $this->Html->image(!empty($ticket['Tech']['page_photo_id']) ? "/page_photos/page_photos/image/".$ticket['Tech']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
		<?= $ticket['Tech']['full_name']; ?>
	</div>
	<? } ?>
	<br/>

	<? if(!empty($ticket['Ticket']['confirmed'])) { ?>
		<div class='bold medium bg-success padding5'>RESOLVED - CONFIRMED</div>
		<?= $this->Time->timebetween($ticket['Ticket']['resolved'],$ticket['Ticket']['created']); ?>
		(<?=$this->Time->mondy($ticket['Ticket']['resolved']); ?>)
	<? } else if (!empty($ticket['Ticket']['resolved'])) { ?>
		<div class='bold medium bg-warning padding5'>RESOLVED - NEEDS TESTING</div>
		<?= $this->Time->timebetween($ticket['Ticket']['resolved'],$ticket['Ticket']['created']); ?>
		(<?=$this->Time->mondy($ticket['Ticket']['resolved']); ?>)
		<? if ($this->Html->me() == $ticket['User']['id']) { ?>
			<h4>Let us know if it's fixed!</h4>
			<?= $this->Form->button($this->Html->g("ok")." Confirm fixed", array('type'=>'submit','name'=>"data[Ticket][confirmed]",'value'=>date("Y-m-d H:i:s"),'class'=>'btn btn-success','div'=>false)); ?>
			<?= $this->Form->button($this->Html->g("exclamation-sign")." It's still not fixed", array('type'=>'submit','name'=>"data[Ticket][resolved]",'value'=>"",'class'=>'btn btn-danger','div'=>false)); ?>
			<br/>
			<?= $this->Form->input("TicketComment.0.comment",array('class'=>'editor mediaonly','rows'=>3,'label'=>'Leave a comment/response...')); ?>
		<? } ?>
	<? } else if (!empty($ticket['Ticket']['deferred'])) { ?>
		<div class='bold medium bg-info padding5'>DEFERRED UNTIL <?= $this->Time->mondy($ticket['Ticket']['deferred']); ?></div>
	<? } else if (!empty($ticket['Ticket']['estimated'])) { ?>
		<div class='bold medium bg-warning padding5'>PENDING</div>
		Estimated resolution<br/><?= $this->Time->timebetween($ticket['Ticket']['estimated']); ?> (<?= $this->Time->mondy($ticket['Ticket']['estimated']); ?>)
		<br/>
		<? if($this->Html->user("manager")) { ?>
			<?= $this->Form->button($this->Html->g("ok")." Mark this issue resolved", array('type'=>'submit','name'=>"data[Ticket][resolved]",'value'=>date("Y-m-d H:i:s"),'class'=>'btn btn-success','div'=>false)); ?>
			<hr/>
			<?= $this->Form->input("deferred", array('type'=>'text','placeholder'=>'mm/dd/yyyy','size'=>10,'class'=>'datepicker','label'=>'Deferred until')); ?>
			<?= $this->Form->button($this->Html->g("remove")." Defer this issue until later", array('type'=>'submit','class'=>'btn btn-danger','div'=>false)); ?>
			<br/>
			<?= $this->Form->input("TicketComment.0.comment",array('class'=>'editor mediaonly','rows'=>3,'label'=>'Explanation...')); ?>
		<? } ?>
	<? } else { ?>
		<div class='bold medium bg-warning padding5'>NEW</div>
		<? if($this->Html->user("manager")) { ?>
			<?= $this->Form->input("estimated", array('type'=>'text','placeholder'=>'mm/dd/yyyy','size'=>10,'class'=>'datepicker','label'=>'Estimated resolution','data-bv-notEmpty'=>'true')); ?>
			<?= $this->Form->button($this->Html->g("scissors")." This issue is being worked on", array('type'=>'submit','class'=>'btn btn-success','div'=>false)); ?>
			<hr/>
			<?= $this->Form->input("deferred", array('type'=>'text','placeholder'=>'mm/dd/yyyy','size'=>10,'class'=>'datepicker','label'=>'Deferred until')); ?>
			<?= $this->Form->button($this->Html->g("remove")." Defer this issue until later", array('type'=>'submit','class'=>'btn btn-danger','div'=>false)); ?>
			<?= $this->Form->input("TicketComment.0.comment",array('class'=>'editor mediaonly','rows'=>3,'label'=>'Explanation...')); ?>
		<? } else { ?>
			<div class='nodata'>This ticket has not been assigned yet</div>
		<? } ?>
	<? } ?>
</div>
<? if($this->Html->user("manager") || $ticket['Ticket']['user_id'] == $this->Html->me()) { ?>
	<?= $this->Form->end(); ?>
<? } ?>

<h3> <?= $ticket['Ticket']['title'] ?> 
</h3>

<p class='medium double'>
	<?= $ticket['Ticket']['description']?>
</p>
<div class='clear'></div>

<? if($this->Html->me() && !$this->Html->user("manager") && $ticket['Ticket']['user_id'] != $this->Html->me()) { # Even after closed ?>
	<? $notifieds = Set::extract("/TicketNotification/user_id", $ticket); ?>
	<? if($this->Html->me() && !in_array($this->Html->me(), $notifieds)) { ?>
		<?= $this->Html->blink("warning-sign", !empty($ticket['Ticket']['confirmed']) ? "I am still experiencing this problem" : "I am experiencing this problem", array('user'=>1,'action'=>'subscribe',$id),array('class'=>'btn-warning')); ?> 
	<? } else { ?>
		<?= $this->Html->blink("remove", "Stop notifying me", array('user'=>1,'action'=>'unsubscribe',$id),array('class'=>'btn-default')); ?> of updates
	<? } ?>
<? } ?>

<hr/>

	<? if(!$this->Html->me()) { ?>
	<hr/>
		<?= $this->Html->blink("user", "Sign in", "/user/users/login",array('class'=>'btn-success')); ?> to add comments or be notified when this issue is resolved
	<? } ?>

<hr/>

<? if(!empty($ticket['TicketComment']) || $this->Html->me()) { ?>
<h4>Comments:</h4>
<? if(!empty($ticket['TicketComment'])) { ?>
	<?foreach($ticket['TicketComment'] as $comment) { ?>
	<div id="comment_<?= $comment['id'] ?>" class='lightgreybg margin5 row'>
		<div class='col-md-2'>
			<?= $this->Html->image(!empty($comment['User']['page_photo_id']) ? "/page_photos/page_photos/image/".$comment['User']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
			<?= $comment['User']['full_name'] ?>
			<br/>
			<?= $this->Time->smarttime($comment['created']); ?>
		</div>
		<div class='col-md-10'>
			<?= ($comment['comment']) ?>
		</div>
	</div>
	<?}?>
<? } ?>
<? if($this->Html->me()) { # Always even if closed ?>
	<div class='padding5 margin5 row'>
		<div class='col-md-2'>
			<?= $this->Html->image(!empty($current_user['page_photo_id']) ? "/page_photos/page_photos/image/".$current_user['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
			<?= $current_user['full_name'] ?>
		</div>
		<div class='col-md-10'>
		<?= $this->Form->create("TicketComment", array('url'=>array('user'=>1,'controller'=>'tickets','action'=>'comment',$ticket['Ticket']['id']))); ?>
			<?= $this->Form->hidden("ticket_id",array('value'=>$ticket['Ticket']['id'])); ?>
			<?= $this->Form->input("comment",array('rows'=>3,'label'=>false,'placeholder'=>'Add a comment...','class'=>'editor mediaonly')); ?>
			<?= $this->Form->save("Add comment"); ?>
		<?= $this->Form->end(); ?>
		</div>
	</div>
<? } ?>

<? } ?>
</div>
