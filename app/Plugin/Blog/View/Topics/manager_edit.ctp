<? $id = !empty($this->data['Topic']['id']) ? $this->data['Topic']['id'] : null; ?>
<?= $this->assign("title", !empty($id) ? "Edit Blog Topic" : "Add Blog Topic"); ?>
<div class="blogTopics form width600">
<?php echo $this->Form->create('Topic'); ?>
		<?= $this->Form->input('id'); ?>
		<?= $this->Form->title(); ?>
		<?= $this->Form->input("ix"); ?>
		<?= $this->Form->input("draft"); ?>

		<?= $this->Form->content(); ?>

	<?= $this->Form->save(); ?>
<?php echo $this->Form->end(); ?>
</div>
