<? $this->assign("page_title", "Change Plan"); ?>
<div class='form'>
<?= $this->Form->create("Rescue"); ?>
	<?= $this->Form->input("plan",array('empty'=>'Free Trial')); ?>
	<?= $this->Form->save("Save"); ?>
<?= $this->Form->end(); ?>
</div>
