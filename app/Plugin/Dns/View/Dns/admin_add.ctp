<? $this->assign("page_title", "Add Website Domain Name"); ?>
<? $this->start("title_controls");?>
	<?= $this->Html->back("Back to Settings", array('action'=>'view')); ?>
<? $this->end("title_controls"); ?>
<div class="lightgreybg border padding50 row">
	<div class='col-md-6'>
		<?= $this->Html->blink("search", "Find an available domain", array('action'=>'search'),array('class'=>'btn-primary controls')); ?>
	</div>

	<div class='col-md-6'>
		<?= $this->Html->blink("edit", "Use an existing domain", array('action'=>'edit'),array('class'=>'btn-success controls')); ?>
	</div>
</div>
