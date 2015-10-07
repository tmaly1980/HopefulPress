<? $pid = !empty($project['Project']['id']) ? $project['Project']['id'] : null; ?>
<?= $this->assign("page_title", "Change Project Owner"); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->back("View project users", array('action'=>'users','project_id'=>$pid)); ?>
<? $this->end("admin_controls"); ?>

<div class="projects form">
<?php echo $this->Form->create('Project'); ?>
	<?= $this->Form->hidden('id'); ?>

	<?= $this->Form->input("user_id", array('label'=>'Project Owner')); ?>


	<?= $this->Form->save("Update Project Owner", array('Xclass'=>'block marginbottom10 margintop25')); ?>
<?php echo $this->Form->end(); ?>
</div>

