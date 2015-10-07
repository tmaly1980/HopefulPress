<? $this->assign("page_title", "Forum Discussions"); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->user("manager")) { ?>
	<?= $this->Html->add("Add discussion",array('manager'=>1,'action'=>'add')); ?>
<? } ?>
<? $this->end("title_controls"); ?>
<div class='index'>
	<? foreach($discussions as $discussion) { 
		$lastMessage = !empty($discussion['Message']) ? $discussion['Message'][count($discussion['Message'])-1] : null; 
	?>
	<div class='lightgreybg margin10 padding10'>
		<div class='right'><?= $total = count($discussion['Message']) ?> <?=$total == 1 ? "reply" : "replies" ?></div>

		<?= $this->Html->link($discussion['Discussion']['title'], array('action'=>'view',$discussion['Discussion']['id']),array('class'=>'bold medium')); ?>
		<? if(!empty($discussion['Discussion']['description'])) { ?>
		<div class=''>
			<?= $this->Text->truncate($discussion['Discussion']['description']); ?>
		</div>

		<? } ?>
		<hr/>

		<? if(!empty($lastMessage)) { ?>
		<div>
			On <?= $this->Time->mondyhms($lastMessage['created']); ?>,
			<?= $lastMessage['User']['full_name'] ?> wrote:
		</div>
		<p>
			<?= $this->Text->truncate($lastMessage['message']); ?>

			<?= $this->Html->link("read more...", array('action'=>'view',$discussion['Discussion']['id'],'#'=>"message_".$lastMessage['id']),array('class'=>'')); ?>
		</p>
		<? } ?>
	</div>
	<? } ?>
</div>
