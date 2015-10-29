<div class='form'>
<?= $this->Form->create("Adopter",array('class'=>'')) ?>
	<?= $this->Form->hidden("id"); ?>
	<?= $this->Form->input("Adoptable.id",array('id'=>'AdoptionStatus','label'=>'Choose an available adoptable (optional)','options'=>$adoptables,'empty'=>' - ')); ?>
	<?= $this->Form->input("status",array('id'=>'AdoptionStatus','options'=>$statuses)); ?>
	<?= $this->Form->input("comments",array('rows'=>5)); ?>
	<div class='row'>
		<div class='col-md-6'>
			<? if(!empty($this->request->data['Adopter']['id'])) { ?>
				<?= $this->Html->delete("Delete application", array('action'=>'delete',$this->request->data['Adopter']['id']),array('confirm'=>"Are you sure you want to delete this application? Once it is removed, it cannot be recovered.")); ?>
			<? } ?>
		</div>
		<?= $this->Form->save("Update",array('div'=>'col-md-6 right_align','cancel_js'=>"$.dialogclose();")); ?>
	</div>
<?= $this->Form->end(); ?>
</div>
