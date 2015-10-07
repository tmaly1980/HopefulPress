<!-- might be able to migrate 'adding' to use this modal too... -->
<? $adding = empty($this->data['Adoptable']['id']); ?>
<?
	$label_alt = null;
	if(!empty($adoptables)) # Adopted.
	{
		$label_alt = "<br/>(or ".$this->Html->link("select existing adoptable",array('action'=>'select'),array('update'=>'Adoptable')).")";
	}

?>
<!-- we have to add a location WITH the event, since we cant handle nested forms -->
<div class="Adoptable">
	<?= $this->Form->hidden('Adoptable.id'); ?>
	<?= $this->Form->input('Adoptable.name', array('label'=>"Adoptable's name",'label_alt'=>$label_alt)); ?>
	<div class='row'>
		<?= $this->Form->input('Adoptable.species', array('div'=>'col-md-6','id'=>'AdoptableSpecies','label'=>"Species",'options'=>$species)); ?>
		<?= $this->Form->input('Adoptable.breed', array('div'=>'col-md-6','id'=>'AdoptableBreed','label'=>"Breed",'options'=>array())); ?>
		<?= $this->Form->input('Adoptable.birthdate', array('div'=>'col-md-6','type'=>'text','class'=>'datepicker','tip'=>'Guess or leave blank')); ?>
		<?= $this->Form->input('Adoptable.date_adopted', array('div'=>'col-md-6','type'=>'text','class'=>'datepicker')); ?>
	</div>
	<div  class='row'>
		<?= $this->Form->input('Adoptable.Owner.primary_first_name', array('div'=>'col-md-6','type'=>'text','label'=>'Owner First Name')); # WIll this work, belongsTo of belongsTo in saveAll() ? ?>
		<?= $this->Form->input('Adoptable.Owner.primary_last_name', array('div'=>'col-md-6','type'=>'text','label'=>'Owner Last Name')); ?>
	</div>
	<?= $this->Form->hidden('Adoptable.status', array('value'=>'Adopted')); ?>
	<?#  probably  not gonna work, oh  well. =$this->element("PagePhotos.edit",array('photoID'=>'AdoptablePagePhoto','modelClass'=>'Rescue.Adoptable')); # Will this work? ?>

	<div class="clear"></div>
</div>

<script>
/* rescue may specialize in one species or even one breed, so we should simplify options!!! */
var breeds = <?= json_encode($breeds); ?>;
$('#AdoptableSpecies').change(function() {
	var species = $(this).val();
	var breedsForSpecies = breeds[species];
	$('#AdoptableBreed').setoptions(breedsForSpecies,null,null,'Unknown');
});
$(document).ready(function() {
	$('#AdoptableSpecies').change();
	$('#Adoptable .datepicker').datepicker({autoclose:true,todayHighlight:true,toggleActive:true});
});
</script>
