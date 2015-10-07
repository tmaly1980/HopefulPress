<? $did  = $discussion['Discussion']['id']; ?>
<? $this->assign("page_title", $discussion['Discussion']['title']); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All discussions",array('action'=>'index')); ?>
	<? if($this->Html->user("manager")) { ?>
	<?= $this->Html->edit("Edit",array('manager'=>1,'user'=>null,'action'=>'edit',$did)); ?>
	<?= $this->Html->delete("Delete discussion",array('manager'=>1,'user'=>null,'action'=>'delete',$did),array('confirm'=>"Are you sure you want to remove this discussion?")); ?>
	<? } ?>
<? $this->end("title_controls"); ?>
<div class='index'>

<div align='right'>
Last updated: <?= $this->Time->mondyhms($discussion['Discussion']['modified']); ?>
</div>

<div class='medium marginbottom25'>
<?= nl2br($discussion['Discussion']['description']); ?>
</div>

<? foreach($discussion['Message'] as $message) { ?>
<div id='message_<?= $message['id'] ?>' class='lightgreybg margin10 padding10 row'>
	<div class='col-md-2'>
		<?= $this->Html->image(!empty($message['User']['page_photo_id']) ? "/page_photos/page_photos/image/".$message['User']['page_photo_id']."/100x100" : "/images/person.png",array('class'=>'width100')); ?>
		<div>
			<?= !empty($message['user_id']) ? $message['User']['full_name'] : "<i>Unknown</i>" ?>
		</div>
	</div>
	<div class='col-md-10'>
		<div class='bold marginbottom10'>
			<?= $this->Time->mondyhms($message['created']); ?>
		</div>
		<? if($message['user_id'] == $this->Html->me() || $this->Html->user("manager")) { ?>
		<div class='right'>
		<?= $this->Html->delete("", array('user'=>1,'controller'=>'messages','action'=>'delete',$message['id']),array('class'=>'btn-xs','confirm'=>"Are you sure about deleting this message?")); ?>
		</div>
		<? } ?>

		<?= ($message['message']); ?>

		<div id='message_likes_<?= $message['id'] ?>'>
			<?= $this->element("../Messages/user_like",array('message'=>$message)); ?>
		</div>
	</div>
</div>
<? } ?>
<div>
<? if($this->Html->me()) { ?>
<div class='lightgreybg margin10 padding10 row'>
	<div class='col-md-2'>
		<?= $this->Html->image(!empty($current_user['page_photo_id']) ? "/page_photos/page_photos/image/".$current_user['page_photo_id']."/100x100" : "/images/person.png",array('class'=>'width100')); ?>
		<div>
			<?= !empty($current_user['full_name']) ? $current_user['full_name'] : "<i>Unknown</i>" ?>
		</div>
	</div>
	<div class='col-md-10'>
	<?= $this->Form->create("Message",array('url'=>array('user'=>1,'controller'=>'messages','action'=>'edit'))); ?>
	<?= $this->Form->input("discussion_id",array('value'=>$did,'type'=>'hidden')); ?>
	<?= $this->Form->input("Discussion.id",array('value'=>$did,'type'=>'hidden')); ?>
	<?= $this->Form->input("Discussion.modified",array('value'=>null,'type'=>'hidden')); # ?>
	<?= $this->Form->input("message",array('class'=>'editor mediaonly','label'=>false)); #"Add message")); ?>
	<?= $this->Form->save("Reply"); ?>
	<?= $this->Form->end(); ?>
	</div>
</div>
<? } else { ?>
	<?= $this->Html->blink("user", "Sign in","/user/users/login",array('class'=>'btn btn-success')); ?> to post a reply.
<? } ?>
</div>
</div>
