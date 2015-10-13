<? $id = !empty($this->request->data["AboutPageBio"]["id"]) ? $this->request->data["AboutPageBio"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Staff Biography" : "Add Staff Biography"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->blink("back", "View About Page", "/about"); ?>
<? $this->end(); ?>

<div class="pages form">
<?= $this->Form->create("AboutPageBio"); ?>
	<?= $this->Form->input('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>

<div class='row'>
	<div class='col-md-4'>
		<?= $this->element("PagePhotos.edit"); ?>
	</div>
	<div class='col-md-8'>
	<?php echo $this->Form->input('name', array('placeholder'=>'Name','div'=>'col-md-6','label'=>false)); ?>
	<?php echo $this->Form->input('title', array('placeholder'=>'Title','div'=>'col-md-6','label'=>false)); ?>
	<?php echo $this->Form->input('description', array('label'=>false,'div'=>'col-md-12','placeholder'=>"Description goes here")); ?>
	</div>
</div>
	<div class='clear'></div>

	<div align='right'>
		<?= $this->Form->save(!$id?"Add Bio":"Update Bio"); ?>
	</div>

<?= $this->Form->end(); ?>
</div>
