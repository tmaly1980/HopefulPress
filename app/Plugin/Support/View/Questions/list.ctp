	<? foreach($questions as $question) { ?>
	<div class='row margintop15 lightgreybg'>
		<div class='col-md-2 center_align'>
			<?= $this->Time->smarttime($question['Question']['created']); ?>
			<? if(!empty($question['User']['id'])) { ?>
			<div>
				<?= $this->Html->image(!empty($question['User']['page_photo_id']) ? "/page_photos/page_photos/image/".$question['User']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
				<br/>
				<?= $question['User']['full_name']; ?>
			</div>
	 		<? } ?>
		</div>
		<div class='col-md-10'>
			<div class='medium bold marginbottom10'><?= $this->Html->link($question['Question']['title'],array('action'=>'view',$question['Question']['id'])); ?>
			<? if($question['Question']['status'] == 'accepted') { ?>
				<span class='bg-success padding5'>ANSWERED</span>
			<? } else if (!empty($question['Question']['answer'])) { ?>
				<span class='bg-info padding5'>PENDING</span>
			<? } else { ?>
				<span class='bg-warning padding5'>UNANSWERED</span>
			<? } ?>
			</div>
			<p>
				<?= $this->Text->truncate($question['Question']['description']); ?>
			</p>
			<? if(!empty($question['Question']['answered'])) { ?>
			Answered in <?= $this->Html->link($this->Time->timebetween($question['Question']['answered'], $question['Question']['created']), "javascript:void(0)",array('title'=>$this->Time->mondyhm($question['Question']['answered']))); ?>
			<? if(!empty($question['Question']['answerer_user_id'])) { ?>
				by 
				<?= $this->Html->image(!empty($question['Answerer']['page_photo_id']) ? "/page_photos/page_photos/image/".$question['Answerer']['page_photo_id']."/36x36" : "/images/person.png",array('class'=>'width40')); ?>
				<?= $question['Answerer']['full_name'] ?>
			<? } ?>
			<? } ?>
		</div>
	</div>
	<? } ?>

