<? $this->assign("page_title", "Add Page"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->blink("back", "All Pages", array("action"=>"index")); ?>
<? $this->end(); ?>

<div class="pages form">
	<ul>
	<? foreach($templates as $template=>$name) { ?>
		<li><?= $this->Html->blink('add', $name, array('action'=>'edit',$template), array()); ?>
	<? } ?>
		<li><?= $this->Html->blink('add', "Other Page", array('action'=>'edit'), array()); ?>
	</ul>

</div>
