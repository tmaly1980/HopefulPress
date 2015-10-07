<? $id = null; #!empty($this->request->data['Subscriber']['id']) ? $this->request->data['Subscriber']['id'] : null; ?>
<? $this->assign("page_title", $id?"Edit Subscriber":"Add Subscriber"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All Subscribers", array('action'=>'index')); ?>
<? $this->end("title_controls"); ?>
<div>
<?= $this->Form->create("Subscriber"); ?>
	<?= $this->Form->input("id"); ?>
	<? if($id) { # Can't change email without them opting in, so create instead. ?>
	<div class='bold'><?= $this->request->data['Subscriber']['email']; ?></div>
	<? } else { ?>
	<?= $this->Form->input("email",array('required'=>1)); ?>
	<? } ?>
	<?= $this->Form->input("fname",array('label'=>'First name')); ?>
	<?= $this->Form->input("lname",array('label'=>'Last name')); ?>
<?= $this->Form->save("Save"); ?>

<?= $this->Form->end(); ?>
</div>
