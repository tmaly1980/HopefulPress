<div class='form'>
<?= $this->Form->create("Adoption",array('class'=>'')) ?>
	<?= $this->Form->hidden("id"); ?>
	<?= $this->Form->input("status",array('id'=>'AdoptionStatus','options'=>array_combine($statuses,$statuses))); ?>
	<?= $this->Form->save("Update",array('cancel_js'=>"$.dialogclose();")); ?>
<?= $this->Form->end(); ?>
</div>
