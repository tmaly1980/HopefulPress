<? if(!isset($model)){ $model= $this->Form->defaultModel; } ?>
<h3>History of Pet Ownership</h3>
<div id='' class='row'>
	<?= $this->Form->input("$model.pet_ownership_history.current_animals", array('required'=>false,'div'=>'col-md-6','type'=>'textarea','label'=>"What animals currently live in the household?",'note'=>"Please list type of animal, name, sex, age, and how long you have owned them, and if they are inside/outside animals")); ?>

	<?= $this->Form->input("$model.pet_ownership_history.previous_pets", array('required'=>false,'div'=>'col-md-6','label'=>"Please list any pets you have owned in the past",'note'=>"Name, breed, how long owned, what happened to it, age it died or left you",'type'=>'textarea')); ?>
</div>


