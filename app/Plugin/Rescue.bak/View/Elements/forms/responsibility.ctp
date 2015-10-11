<h3>Care and Responsibility</h3>
<div id='' class='row'>
	<?= $this->Form->input("Adoption.care_and_responsibility.time_left_alone", array('div'=>'col-md-6','label'=>"Approximately how many hours a day will the animal be left alone?","required"=>true)); ?>
	<?= $this->Form->input("Adoption.care_and_responsibility.time_left_outside", array('div'=>'col-md-6','label'=>"How often will your animal be left outside?","required"=>true)); ?>
	<?= $this->Form->input("Adoption.care_and_responsibility.who_will_care_for", array('required'=>true,'div'=>'col-md-6','label'=>"Who will have primary responsibility for the care (food, training, exercise) of your new pet?",'required'=>true)); ?>
	<?= $this->Form->input("Adoption.care_and_responsibility.caregiver_during_travel", array('label'=>"When you travel, who will care for your pet?","required"=>true,'div'=>'col-md-6')); ?>
	<?= $this->Form->input("Adoption.care_and_responsibility.pet_move_with_you", array('label'=>"If you move, will your new pet go with you?","options"=>$this->Form->yesno,"required"=>true,'div'=>'col-md-6')); ?>
	<?= $this->Form->input("Adoption.care_and_responsibility.everyone_in_household_wants_pet", array('label'=>"Does everyone in your household want the same kind of pet?",'options'=>$this->Form->yesno,"required"=>true,'div'=>'col-md-6')); ?>
	<?= $this->Form->input("Adoption.care_and_responsibility.how_discipline", array('label'=>"What is your definition of disciplining your pet?","required"=>true,'div'=>'col-md-6')); ?>
	<?= $this->Form->input("Adoption.care_and_responsibility.what_do_if_cant_get_along", array('label'=>"What will you do if someone in your household cannot get along with your new pet?","required"=>true,'div'=>'col-md-6')); ?>
	<?= $this->Form->input("Adoption.care_and_responsibility.when_would_give_up_pet", array('label'=>"Under what circumstances would you give up your new pet?","required"=>true,'div'=>'col-md-6')); ?>

	<?= $this->Form->input("Adoption.care_and_responsibility.ensure_current_on_vaccinations", array('label'=>"Are you willing to ensure your pet stays current on vaccinations?",'options'=>$this->Form->yesno,"required"=>true,'div'=>'col-md-6')); ?>

	<?= $this->Form->input("Adoption.care_and_responsibility.ensure_leashed_on_walks", array('label'=>"Are you willing to ensure your pet stays leashed on walks?",'options'=>$this->Form->yesno,"required"=>true,'div'=>'col-md-6')); ?>

	<?= $this->Form->input("Adoption.care_and_responsibility.prepared_for_vet_costs", array('label'=>"Are you prefered to deal with both routine veterinary costs (worms, shots) and non-routine/emergency vet care, especially as the animal gets older?",'options'=>$this->Form->yesno,"required"=>true,'div'=>'col-md-6')); ?>

	<?= $this->Form->input("Adoption.care_and_responsibility.how_much_willing_to_spend_on_yearly_medical_expenses", array('label'=>"How much would you be willing to spend on medical expenses per year for your pet?","required"=>true,'div'=>'col-md-6')); ?>

</div>

