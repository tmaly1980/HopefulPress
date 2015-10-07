<?#= $this->Admin->unloadWarn(); ?>
<?= $this->assign("page_title", "Edit Members Page"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("View members area", array('members'=>1,'action'=>'view')); ?>
<? $this->end("title_controls"); ?>
<div class="projects form">
<?php echo $this->Form->create('MemberPage'); ?>
	<?= $this->Form->hidden('id'); ?>

<?= $this->Form->title(null, array('div'=>'col-md-6 margintop15')); ?>

<div class='row'>
	<div class='col-md-6 col-md-push-6'>
		<?= $this->element("PagePhotos.edit"); ?>
	</div>
	<div class='col-md-6 col-md-pull-6'>
		<?= $this->Form->content('description',array('label'=>'Description')); ?>
		<?= $this->Form->save("Save Member Page"); ?>
	</div>
</div>

<div class='clear'></div>
<?php echo $this->Form->end(); ?>
</div>
