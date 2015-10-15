<div class='form'>
<?= $this->Form->create("Adopter",array('class'=>'')) ?>
	<?= $this->Form->hidden("id"); ?>
	<?= $this->Form->input("Adoptable.id",array('id'=>'AdoptionStatus','label'=>'Choose an available adoptable (optional)','options'=>$adoptables,'empty'=>' - ')); ?>
	<?= $this->Form->input("status",array('id'=>'AdoptionStatus','options'=>$statuses)); ?>
	<?= $this->Form->save("Update",array('cancel_js'=>"$.dialogclose();")); ?>
<?= $this->Form->end(); ?>
</div>
