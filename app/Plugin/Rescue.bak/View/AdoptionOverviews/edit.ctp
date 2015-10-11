<? $rescue = $this->Site->get("rescue_enabled"); ?>
<? $mode = $rescue?"adoption":"sponsorship" ?>
<? $ucmode = ucwords($mode); ?>
<? $person = $rescue?"adopter":"sponsor" ?>
<? $this->assign("page_title", "Edit $ucmode Overview"); ?>

<div class='form'>
<?= $this->Form->create("AdoptionOverview"); ?>
	<?= $this->Form->hidden("id"); ?>

<div class='row'>
	<!--<div class='col-md-6 pull-right'>-->
		<?= $this->element("PagePhotos.edit"); ?>
	<!-- TITLE  IS AUTOMATIC NOW... ADOPTION vs SPONSORSHIP
	</div>
	<div class='col-md-6 push-left'>
	<?#= $this->Form->title(); ?>
	</div>
	-->
</div>

	<?= $this->Form->input("introduction", array('class'=>'editor')); ?>
	<div class='alert alert-info'>
		Here you can describe your <?= $mode ?> process as well as expectations toward a potential <?= $person ?>
	</div>

	<div class='clear'></div>

	<?= $this->Form->save("Update introduction",array('cancel'=>($rescue?"/adoption":"/sanctuary"))); ?>
<?= $this->Form->end(); ?>
</div>
<div class='alert alert-info'>
You'll be able to add further pages, files, and frequently asked questions on the next page.
</div>
