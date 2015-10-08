<h3>Specialization</h3>
<div class='alert alert-info'>
	Which species, breeds, sizes, age groups etc you mainly work with, plus how many adoptables (including those currently listed online) you can care for at one time.

	<p>When searching for an appropriate rescue, adoptive families, shelters, or those who find a stray/surrender an animal
	will be able to find you.
</div>
	<!--
	all animals
	all dogs
	pitbulls only
	seniors only
	senior pitbulls only
	small dogs only
	small birds (indoor) only
	*** MIGHT ALSO BE MULTIPLE SPECIFIC BREEDS ***
	probably better as hasMany
	-->
<table id='Specializations' class='table'>
	<thead>
	<tr>
		<th>Species</th>
		<th>Breed</th>
		<th>Size</th>
		<th>Age</th>
		<th>Limit</th>
	</tr>
	</thead>
	<tbody>
	<? $n = 0; do { $id = !empty($this->request->data['Specialization'][$n]['id']) ? $this->request->data['Specialization'][$n]['id'] : null; ?>
	<tr class='Specialization'>
		<td>
			<?= $this->Form->hidden("Specialization.$n.id",array('class'=>'SpecializationID')); ?>
			<?= $this->Form->input("Specialization.$n.species",array('label'=>false,'class'=>'SpecializationSpecies','options'=>$species,'empty'=>'- All Species -')); ?>
		</td>
		<td><?= $this->Form->input("Specialization.$n.breed",array('label'=>false,'class'=>'SpecializationBreed','options'=>array(),'empty'=>'- Any -')); ?></td>
		<td><?= $this->Form->input("Specialization.$n.adult_size",array('label'=>false,'options'=>$adultSizes,'empty'=>'- Any -')); ?></td>
		<td><?= $this->Form->input("Specialization.$n.age_group",array('label'=>false,'options'=>$ageGroups,'empty'=>'- Any -')); ?></td>
		<td><?= $this->Form->input("Specialization.$n.limit",array('label'=>false,'size'=>4)); ?></td>
		<td>
			<?= $this->Html->remove("", "javascript:void(0)",array('class'=>'SpecializationRemove')); ?>
		</td>

	</tr>
	<? } while(!empty($this->request->data['Specialization']) && $n++ < count($this->request->data['Specialization'])); ?>
	</tbody>
	<tfoot>
		<td colspan=6>
		<?= $this->Html->add("Add another",'javascript:void(0)', array('id'=>'SpecializationAdd')); ?>
		</td>
	</tfoot>
</table>
<script>
$('#SpecializationAdd').click(function() {
	var lastrow = $('table#Specializations tbody tr:last');
        var row = lastrow.addFormRow(); // Takes self, duplicates as later sibling.

	// Assume same species (and list of breeds - already there)
	row.find('.SpecializationSpecies').val(lastrow.find('.SpecializationSpecies').val());
});
$('body').on('click', '.SpecializationRemove', function() { // Since onthefly added
	var row = $(this).closest('tr');
	var id = row.find('.SpecializationID').val();
	// If existing record, remove from db via json
	if(id)
	{
		$.post('/rescuer/rescue/<?= $this->request->data['Rescue']['hostname']; ?>/specializations/delete/'+id, function () {
			row.remove();
		});
	} else {
		row.remove();
	}
});
//////////////////////////////////////////
var breeds = <?= json_encode($breeds); ?>;
$('.SpecializationSpecies').change(function() {
	var species = $(this).val();
	var breedsForSpecies = breeds[species];
	var row = $(this).closest('tr');
	$(row).find('.SpecializationBreed').setoptions(breedsForSpecies,null,null,'- Any -');
});
</script>
<style>
#Specializations tr.Specialization:only-child .SpecializationRemove
{
	display:none;
}
</style>
<br/>
<br/>
<div>
	<? /* foreach($species as $spec=>$specName) { ?>
		<?= $this->Form->checkbox("specialization.species",array('value'=>$spec)); ?>
		<?= $this->Form->label("specialization.species",$specName); ?>

		<div style='display: none;'>
			<?= $this->Form->input("restrictions.capacity",array('note'=>'How many of this species/breed')); ?>
			something species as a whole, and/or breed specific.
			<?= $this->Form->input("restrictions.0.breed",array('options'=>$species,'type'=>'checkbox','class'=>'species')); ?>
			<?= $this->Form->input("restrictions.0.capacity",array('note'=>'How many of this species/breed')); ?>
		</div>
		<!-- do we ask how many they have currently? will site automatically-->
	<? } */ ?>
	<div style='display: none;'>
	</div>

	<?= $this->Form->input("specialization_details",array('rows'=>4)); ?>
</div>

