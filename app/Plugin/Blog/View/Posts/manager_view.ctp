<? $this->start("title_controls"); ?>
	<?= $this->Html->edit("Edit Post", array('action'=>'edit', $post['Post']['id']), array('class'=>'color')); ?>
	<?= $this->Html->delete("Delete Post", array('action'=>'delete', $post['Post']['id']), array('class'=>'color','confirm'=>'Are you sure you want to delete this post?')); ?>
<? $this->end(); ?>

<?= $this->element("../Posts/view"); ?>
