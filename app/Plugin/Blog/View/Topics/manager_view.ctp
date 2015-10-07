<? $this->assign("admin_title", "Manage Topics"); ?>

<? $this->start("title_controls"); ?>
	<?= $this->Html->link("Edit Topic", array('action'=>'edit', $topic['Topic']['id']), array('class'=>'color')); ?>
	<?= $this->Form->postLink("Delete Topic", array('action'=>'delete', $topic['Topic']['id']), array('class'=>'color','confirm'=>'Are you sure you want to delete this topic?')); ?>
<? $this->end(); ?>

<?= $this->element("../Topics/item"); ?>
