<? $this->start("title_controls"); ?>
	<?= $this->Html->link("New Topic", array('action'=>'add'), array('class'=>'color green')); ?>
<? $this->end(); ?>

<?= $this->element("../Topics/index"); ?>
