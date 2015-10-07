<?#= $this->Admin->unloadWarn(); ?>
<? $pid = !empty($this->data['Project']['id']) ? $this->data['Project']['id'] : null; ?>
<?= $this->assign("page_title", !empty($pid) ? 'Edit Project' : 'Add Project'); ?>
<? $this->start("admin_controls"); ?>
<? if(empty($pid)) { ?>
	<?= $this->Html->back("All projects", array('action'=>'index')); ?>
<? } else { ?>
	<?= $this->Html->back("View project", array('admin'=>false,'action'=>'view','project_id'=>$pid)); ?>
<? } ?>
<? $this->end("admin_controls"); ?>
<div class="projects form">
<?php echo $this->Form->create('Project'); ?>
	<?= $this->Form->hidden('id'); ?>

<?= $this->Form->title(null, array('div'=>'col-md-6 margintop15')); ?>

<div class='row'>
	<div class='col-md-6 col-md-push-6'>
		<?= $this->element("PagePhotos.edit"); ?>
	</div>
	<div class='col-md-6 col-md-pull-6'>
		<?= $this->Form->content('content',array('label'=>'Description')); ?>
		<?= $this->Form->save(!$pid ? "Create Project" : "Update Project", array('class'=>'block marginbottom10 margintop25')); ?>
	</div>
</div>

<div class='clear'></div>
<?php echo $this->Form->end(); ?>
</div>
