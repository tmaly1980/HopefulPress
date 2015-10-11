<? $this->assign("page_title", "Edit Volunteer Overview"); ?>

<div class='form'>
<?= $this->Form->create("VolunteerPageIndex"); ?>
	<?= $this->Form->hidden("id"); ?>

<div class='row'>
	<div class='col-md-6 pull-right'>
		<?= $this->element("PagePhotos.edit"); ?>
	</div>
	<div class='col-md-6 push-left'>
	<?= $this->Form->title(); ?>
	</div>
</div>

	<?= $this->Form->content("introduction", array('class'=>'editor')); ?>
	<div class='alert alert-info'>
		Here you can describe your volunteer process as well as expectations toward a potential volunteer.
	</div>

	<div class='clear'></div>

	<?= $this->Form->save("Update introduction",array('cancel'=>true)); ?>
<?= $this->Form->end(); ?>
</div>
<div class='alert alert-info'>
You'll be able to add further pages, files, and frequently asked questions on the next page.
</div>
