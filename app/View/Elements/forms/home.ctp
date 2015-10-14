<!-- this needs to be tweaked, ie if the rescue doesn't even have dogs - look up Restrictions -->
<? $species = !empty($adoptionForm['AdoptionForm']['species']) ? $adoptionForm['AdoptionForm']['species'] : 'Dog';  ?>
<? $dog = (strtolower($species) == 'dog'); ?>
<? if(empty($model)) { $model = $this->Form->defaultModel; } ?>

		<h3>About Your Home</h3>
		<div id='' class='row'>
			<?= $this->Form->input("$model.home_details.household_size", array('required'=>false,'div'=>'col-md-4','size'=>6,'type'=>'text','label'=>"# in household?")); ?>
			<?= $this->Form->input("$model.home_details.ages_children", array('required'=>false,'div'=>'col-md-4','size'=>8,'type'=>'text','label'=>"Age of child(ren), if any?")); ?>
			<?= $this->Form->input("$model.home_details.own_rent", array('required'=>true,'div'=>'col-md-4','type'=>'select','options'=>array('Own'=>'Own','Rent'=>'Rent'),'label'=>'Own or rent?')); ?>
		</div>
		<div class='row'>
			<?= $this->Form->input("$model.home_details.landlord_allowed", array('div'=>'col-md-4','label'=>"If you rent, does your landlord allow animals?", "required"=>false, 'type'=>'select','options'=>$this->Form->yesnoblank,'default'=>''));  ?>
			<?= empty($dog)?null:$this->Form->input("$model.home_details.landlord_breed_restrictions", array('div'=>'col-md-4','label'=>"If you rent, list breed restrictions, if any", "required"=>false, 'type'=>'text','default'=>''));  ?>
			<?= empty($dog)?null:$this->Form->input("$model.home_details.securely_fenced", array('div'=>'col-md-4','label'=>"Is your yard fully/partially securely fenced?", "required"=>true, 'type'=>'select','options'=>$this->Form->yesno,'default'=>''));  ?>
		</div>
		<? if(!empty($dog)) { ?>
		<div class='row'>
			<?= $this->Form->input("$model.home_details.has_dog_door", array('div'=>'col-md-4','label'=>"Does your home have a dog door?", "required"=>true, 'type'=>'select','options'=>$this->Form->yesno,'default'=>''));  ?>
			<?= $this->Form->input("$model.home_details.fenced_pool", array('div'=>'col-md-4','label'=>"If you have a pool, is it fenced?", "required"=>false, 'type'=>'select','options'=>$this->Form->yesnoblank,'default'=>''));  ?>
			<?= $this->Form->input("$model.home_details.secure_dog_run", array('div'=>'col-md-4','label'=>"If you have a dog run, is it secure?", "required"=>false, 'type'=>'select','options'=>$this->Form->yesnoblank,'default'=>''));  ?>
			<?= $this->Form->input("$model.home_details.gate_secured", array('div'=>'col-md-3','label'=>"Is your gate secure with no gaps?", "required"=>false, 'type'=>'select','options'=>$this->Form->yesnoblank,'default'=>''));  ?>
		</div>
		<div class='row'>
			<?= $this->Form->input("$model.home_details.exercise_and_relieving_details", array('div'=>'col-md-6','label'=>"How do you plan to exercise your dog and allow it to relieve itself?", "required"=>false, 'type'=>'textarea','rows'=>3)); ?>
			<div class='col-md-6'>
				<?= $this->Form->input("$model.home_details.familiar_crate_training", array('label'=>"Are you familiar with crate training?", "required"=>true, 'type'=>'select','options'=>$this->Form->yesno,'default'=>''));  ?>
				<?= $this->Form->input("$model.home_details.familiar_obedience_training", array('Xdiv'=>'col-md-12','label'=>"Are you familiar with obedience training?", "required"=>true, 'type'=>'select','options'=>$this->Form->yesno,'default'=>''));  ?>
			</div>
		</div>
		<? } ?>

		<div id=''  class='row'>
			<?= $this->Form->input("$model.home_details.allergies", array('div'=>'col-md-6','label'=>"Does anyone in the household have allergies to animals?")); ?>
		</div>

