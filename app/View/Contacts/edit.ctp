<? $id = !empty($this->request->data["Contact"]["id"]) ? $this->request->data["Contact"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Contact Details" : "Add Contact Details"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->blink("back", "View Contact Page", "/contact"); ?>
<? $this->end(); ?>

<div class="pages form">
<?= $this->Form->create("Contact"); ?>
	<?= $this->Form->input('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>

<div class='row'>
	<?php echo $this->Form->input('name', array('placeholder'=>'Full Name','div'=>'col-md-3','label'=>false)); ?>
	<?php echo $this->Form->input('title', array('placeholder'=>'Title','div'=>'col-md-3','label'=>false)); ?>
</div>
<div class='row'>
	<?php echo $this->Form->input('phone', array('placeholder'=>'(212) 555-1212','div'=>'col-md-3','label'=>'Primary Phone')); ?>
	<?php echo $this->Form->input('alternate_phone', array('placeholder'=>'(212) 555-1212','div'=>'col-md-3','label'=>'Alternate Phone')); ?>
</div>
<div class='row'>
	<?php echo $this->Form->input('email', array('placeholder'=>'user@domain.com','div'=>'col-md-6','label'=>'Email Address')); ?>
</div>
<div class='row'>
	<?php echo $this->Form->input('details', array('label'=>false,'div'=>'col-md-6','placeholder'=>"Optional description")); ?>
</div>
	<div class='clear'></div>

	<div class='row'>
	<div class='col-md-6' align='right'>
		<?= $this->Form->save(!$id?"Add Contact":"Update Contact"); ?>
	</div>
	</div>

<?= $this->Form->end(); ?>
</div>
