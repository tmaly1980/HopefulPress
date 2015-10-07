<? #$this->assign("page_title", "Edit Homepage"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? /* $this->start("admin_controls"); ?>
	<?= $this->Html->blink("back", "View Homepage", array("action"=>"view")); ?>
<? $this->end(); */ ?>

<div class="pages form">
	<?php echo $this->Form->create('Homepage', array('role' => 'form')); ?>

		<?php echo $this->Form->title('title', array('label'=>false, 'placeholder' => 'Homepage Title'));?>

		<div class='row'>

		<div class='col-md-6'>
			<?= $this->Form->input("introduction", array('label'=>'Introduction','class'=>'autogrow double')); ?>
		</div>
		<div class='col-md-6'>
			<?= $this->element("PagePhotos.edit"); ?>
		</div>

		</div>

		<div align="right">
			<?= $this->Form->save("Update Homepage",array('cancel'=>array('action'=>'view'))); ?>
		</div>
	<?php echo $this->Form->end() ?>

	<div class='clear'></div>
	<script>
	</script>
</div>
