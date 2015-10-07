<? if(!empty($comment)) { ?>
<div id="HpComment_<?= $comment['HpComment']['id'] ?>" class="HpComment lightgreybg margin5">
	<? /* if($this->Admin->me($comment['HpComment']['user_id']) || $this->Admin->is_god()) { ?>
	<?= $this->Html->link("&nbsp;", array('action'=>'delete', $comment['HpComment']['id']), array('title'=>'Delete comment','confirm'=>'Are you sure you want to delete this comment?', 'class'=>'dismiss json')); ?>
	<? } */ ?>

	<div class="timestamp">
		<div class="right"><?= ucfirst($this->Date->timeago($comment['HpComment']['created'])) ?></div>
		<? if(!empty($comment['User']['name'])) { ?><b><?= $comment['User']['name'] ?> wrote:</b><? } ?>
		<? else if(!empty($comment['name'])) { ?><b><?= $comment['name'] ?> wrote:</b><? } ?>
		<div class="clear"></div>
	</div>
	<? if(!empty($comment['HpComment']['title'])) { ?>
	<div class="title bold">
		<?= $comment["HpComment"]['title'] ?>
	</div>
	<? } ?>
	<div class="indent padding5">
		<!-- linkify, etc as well -->
		<?= $this->Text->autoLink(nl2br($comment["HpComment"]['content'])); ?>
		<!-- todo trunkifying w/'more' -->
	</div>
</div>
<? } ?>
