<? $this->assign("page_title", "Edit About Page"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->blink("back", "View About Page", array("action"=>"view")); ?>
<? $this->end(); ?>

<div class="pages form">
	<?php echo $this->Form->create('AboutPage', array('role' => 'form')); ?>

			<?php echo $this->Form->input('title', array('class' => 'font24','Xdiv'=>'col-lg-6 col-md-6', 'label'=>false, 'placeholder' => 'Title','default'=>'About Us'));?>

		<div class='row'>
		<div class='col-md-6 pull-right'>
			<?= $this->element("PagePhotos.edit"); ?>
		</div>

		<div class='col-md-6 push-left'>
			<?= $this->Form->input("overview", array('label'=>'Overview')); ?>
			<?= $this->Form->input("mission", array('label'=>'Mission')); ?>
			<?= $this->Form->input("history", array('label'=>'History')); ?>
		</div>

		</div>

		<div align="right">
			<?= $this->Form->save("Update About Page"); ?>
		</div>
	<?php echo $this->Form->end() ?>

	<div class='clear'></div>
	<script>
	</script>
</div>
