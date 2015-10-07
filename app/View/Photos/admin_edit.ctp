<? $id = !empty($this->data['Photo']['id']) ? $this->data['Photo']['id'] : null; ?>
<div class="photos form">
<?php echo $this->Form->create('Photo'); ?>
	<h2><?= !empty($id) ? 'Edit Photo' : 'Add Photo'; ?></h2>
		<?= $this->Form->input('id'); ?>
		<?= $this->Form->input('photo_url'); ?>
		<?= $this->Form->input('title'); ?>
		<?= $this->Form->input('path'); ?>
		<?= $this->Form->input('thumb_path'); ?>
		<?= $this->Form->input('caption'); ?>
		<?= $this->Form->input('filename'); ?>
		<?= $this->Form->input('ext'); ?>
		<?= $this->Form->input('type'); ?>
		<?= $this->Form->input('size'); ?>
		<?= $this->Form->input('ix'); ?>
		<?= $this->Form->input('photo_album_id'); ?>
	<?= $this->Form->save(); ?>
<?php echo $this->Form->end(); ?>
</div>
