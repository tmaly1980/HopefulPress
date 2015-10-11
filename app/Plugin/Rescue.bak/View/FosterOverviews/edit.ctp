<? $this->assign("page_title", "Edit Foster Overview"); ?>

<div class='form'>
<?= $this->Form->create("FosterOverview"); ?>
	<?= $this->Form->hidden("id"); ?>

<div class='row'>
	<div class='col-md-6 col-md-push-6 padding10'>
		<?= $this->element("PagePhotos.edit"); ?>
	</div>
	<div class='col-md-6 col-md-pull-6'>
	<?= $this->Form->title(); ?>
	</div>
</div>

	<?= $this->Form->content("introduction", array('class'=>'editor')); ?>
	<div class='alert alert-info'>
		Here you can describe your foster process as well as expectations toward a potential foster caregiver.
	</div>

	<div class='clear'></div>

	<?= $this->Form->save("Update introduction",array('cancel'=>true)); ?>
<?= $this->Form->end(); ?>
</div>
<div class='alert alert-info'>
You'll be able to add further pages, files, and frequently asked questions on the next page.
</div>
