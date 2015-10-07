<?= $this->element("Core.js/editor"); ?>
<? $id = $question['Question']['id'];  ?>
<? $this->assign("page_title", "Support Question"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All questions",array('action'=>'index')); ?>
	<? if($this->Html->user("manager")) { ?>
		<?= $this->Html->delete("Remove question",array('manager'=>1,'action'=>'delete',$id),array('confirm'=>'Are you sure you want to remove this question?')); ?>
	<? } ?>
<? $this->end(); ?>

<div class='view'>

<div class='right'>
	Asked <?= $this->Time->smarttime($question['Question']['created']); ?>
	<? if(!empty($question['User']['id'])) { ?>
	<div align='right'>
		<?= $question['User']['full_name']; ?>
		<?= $this->Html->image(!empty($question['User']['page_photo_id']) ? "/page_photos/page_photos/image/".$question['User']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
	</div>
	<? } ?>
</div>

<h3> <?= $question['Question']['title'] ?> 
	<? if($question['Question']['status'] == 'accepted') { ?>
		<span class='bg-success padding5'>ANSWERED</span>
	<? } else if (!empty($question['Question']['answer'])) { ?>
		<span class='bg-info padding5'>PENDING</span>
	<? } else { ?>
		<span class='bg-warning padding5'>UNANSWERED</span>
	<? } ?>
</h3>

<?  if(!empty($question['Question']['question_category_id`'])) { ?>
<p>
Filed under <?= $this->Html->link($question['QuestionCategory']['title'], array('controller'=>'questions','action'=>'category',$question['QuestionCategory']['id'])); ?>
</p>
<? } ?>

<p class='medium double'>
	<?= nl2br($question['Question']['description'])?>
</p>

<? if(($me = $this->Html->me()) && $question['Question']['status'] != 'accepted' && $question['Question']['user_id'] != $me) { ?>
	<? $notifieds = Set::extract("/QuestionNotification/user_id", $question); ?>
	<? if(!in_array($this->Html->me(), $notifieds)) { ?>
		<?= $this->Html->blink("envelope", "Notify me", array('user'=>1,'action'=>'subscribe',$id),array('class'=>'btn-primary')); ?> when answered or otherwise updated
	<? } else { ?>
		<?= $this->Html->blink("remove", "Stop notifying me", array('user'=>1,'action'=>'unsubscribe',$id),array('class'=>'btn-default')); ?> when answered or otherwise updated
	<? } ?>
<? } ?>

<hr/>

<h4> Answer: 
	<?if($question['Question']['status'] == 'accepted') { ?>
		<span class='bg-success padding5'>ACCEPTED</span>
	<? } else if($question['Question']['status'] == 'rejected') { ?>
		<span class='bg-danger padding5'>REJECTED</span>
	<? } else if(!empty($question['Question']['answer'])) { ?>
		<span class='bg-info padding5'>PENDING</span>
	<? } ?>
</h4>
<? if(!empty($question['Question']['answer']) && $question['Question']['status'] != 'rejected') { ?>
<p class=''>
	<?= $question['Question']['answer']?>
</p>
<div class='clear'></div>
	<? if(!empty($question['Question']['answered'])) { ?>
	<hr/>
	<div>
	Answered in <?= $this->Html->link($this->Time->timebetween($question['Question']['answered'], $question['Question']['created']), "javascript:void(0)",array('title'=>$this->Time->mondyhm($question['Question']['answered']))); ?>
	<? if(!empty($question['Question']['answerer_user_id'])) { ?>
		by 
		<?= $this->Html->image(!empty($question['Answerer']['page_photo_id']) ? "/page_photos/page_photos/image/".$question['Answerer']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
		<?= $question['Answerer']['full_name'] ?>
	<? } ?>

	<? if($question['Question']['status'] == 'accepted') { ?>
	<div id='question_likes_<?= $question['Question']['id'] ?>'>
		<?= $this->element("../Questions/user_like",array('question'=>$question)); ?>
	</div>
	<? } ?>

	</div>
	<? } ?>
	<? if($question['Question']['status'] != 'accepted' && $question['Question']['user_id'] == $this->Html->me()) { ?>
	<hr/>
	<h4>Is this answer satisfactory? Please accept the answer in order for the question to be resolved.</h4>
	<?= $this->Form->create("Question", array('url'=>array('user'=>1,'action'=>'status',$question['Question']['id']))); ?>
		<?= $this->Form->hidden("id",array('value'=>$question['Question']['id'])); ?>
		<?= $this->Form->hidden("QuestionComment.0.question_id",array('value'=>$question['Question']['id'])); ?>
		<?= $this->Form->input("QuestionComment.0.comment",array('class'=>'editor  mediaonly','rows'=>3,'label'=>false,'placeholder'=>'Leave a comment/response...')); ?>
		<?= $this->Form->button("Accept", array('type'=>'submit','name'=>"data[Question][status]",'value'=>"accepted",'class'=>'btn btn-success','div'=>false)); ?>
		or
		<?= $this->Form->button("Reject", array('type'=>'submit','name'=>"data[Question][status]",'value'=>"rejected",'class'=>'btn btn-danger','div'=>false)); ?>
		this answer
	<?= $this->Form->end(); ?>
	<? } else if($question['Question']['status'] != 'accepted') { ?>
		<div class='nodata'>The questioner has not verified/approved the answer yet.</div>
	<? } ?>
<? } else if($this->Html->user("manager")) { ?>
<?= $this->Form->create("Question", array('url'=>array('user'=>1,'action'=>'edit',$question['Question']['id']))); ?>
	<?= $this->Form->input("id",array('value'=>$question['Question']['id'])); ?>
	<?= $this->Form->input("answer",array('label'=>false,'class'=>'editor','value'=>$question['Question']['answer'])); ?>
	<?= $this->Form->save("Submit Answer"); ?>
<?= $this->Form->end(); ?>
<? } else { ?>
	<div class='nodata'>This question has not been answered yet</div>
	<br/>
	<? if(!$this->Html->me()) { ?>
		<?= $this->Html->blink("user", "Sign in", "/user/users/login",array('class'=>'btn-success')); ?> to add comments or be notified when this question is answered
	<? } ?>
<? } ?>

<hr/>

<? if(!empty($question['QuestionComment']) || ($this->Html->me() && $question['Question']['status'] != 'accepted')) { ?>
<h4>Comments:</h4>
<? if(!empty($question['QuestionComment'])) { ?>
	<?foreach($question['QuestionComment'] as $comment) { ?>
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
<? if($this->Html->me() && $question['Question']['status']  != 'accepted') { ?>
	<div class='padding5 margin5 row'>
		<div class='col-md-2'>
			<?= $this->Html->image(!empty($current_user['page_photo_id']) ? "/page_photos/page_photos/image/".$current_user['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
			<?= $current_user['full_name'] ?>
		</div>
		<div class='col-md-10'>
		<?= $this->Form->create("QuestionComment", array('url'=>array('user'=>1,'controller'=>'questions','action'=>'comment',$question['Question']['id']))); ?>
			<?= $this->Form->hidden("question_id",array('value'=>$question['Question']['id'])); ?>
			<?= $this->Form->input("comment",array('class'=>'editor  mediaonly','rows'=>3,'label'=>false,'placeholder'=>'Add a comment...')); ?>
			<?= $this->Form->save("Add comment"); ?>
		<?= $this->Form->end(); ?>
		</div>
	</div>
<? } else if ($this->Html->me()) { ?>
	<div class='nodata'>Comments have been closed because the question has been answered.</div>
<? } ?>

<? } ?>

</div>
