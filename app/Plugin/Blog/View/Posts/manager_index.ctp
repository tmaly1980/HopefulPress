<? $this->start("title_controls"); ?>
	<?= $this->Html->add("New Post", array('action'=>'add'), array('class'=>'')); ?>
<? $this->end(); ?>
<?= $this->element("../Posts/index"); ?>
