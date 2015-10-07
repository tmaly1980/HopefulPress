<? $this->assign("page_title", "Customize Foster Application"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("View application", array('action'=>'view')); ?>
<? $this->end("title_controls"); ?>
<div class='form'>
	<?= $this->Form->create("FosterForm"); ?>
	<?= $this->Form->input("introduction",array('class'=>'editor')); ?>
	<!-- todo sections, etc -->
	<?= $this->Form->input("acknowledgment",array('class'=>'editor','title'=>'Disclaimer/Acknowledgment')); ?>
	<?= $this->Form->save("Save"); ?>
	<?= $this->Form->end(); ?>
</div>
