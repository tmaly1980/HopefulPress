<? $this->assign("page_title", "Edit Donation Overview"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("View Donation Page", array('rescuer'=>false,'action'=>'view','rescue'=>$rescuename)); ?>
<? $this->end("title_controls"); ?>

<div class='form'>
<?= $this->Form->create("DonationPage"); ?>
	<?= $this->Form->hidden("id"); ?>

<div class='row'>
	<?#= $this->Form->title(); ?>

	<h3>Introduction</h3>
	<div class='alert alert-info'>
		Here you can describe how donations help your organization. Listing specific contribution levels and what they can be used for can give donors a better understanding of how their contributions help.
	</div>
	<?= $this->Form->content("introduction", array('class'=>'editor')); ?>

	<h3>Wish List</h3>
	<div class='alert alert-info'>
		Sometimes people are only able to (or would prefer to) contribute via various supplies, goods, etc. Listing your needed items here helps them know how else they can help you.
	</div>
	<?= $this->Form->content("wishlist", array('class'=>'editor')); ?>

	<div class='clear'></div>

	<?= $this->Form->save("Update overview",array('cancel'=>false)); ?>
<?= $this->Form->end(); ?>
</div>
