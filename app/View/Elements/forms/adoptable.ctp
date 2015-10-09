<h3>Animal Preference</h3>
<div class='row'>
	<?= $this->Form->input("adoptable_id", array('label'=>"Specify an adoptable you are interested in, if any",'empty'=>'','div'=>'col-md-6','note'=>'Application approval does not guarantee that your specific chosen animal will be available')); ?>

	<?= $this->Form->input("{$this->Form->defaultModel}.preference.gender",array('div'=>'col-sm-3 col-md-3','label'=>'Preferred gender','options'=>array(''=>' - ','Male'=>'Male','Female'=>'Female'))); ?>
	<?= $this->Form->input("{$this->Form->defaultModel}.preference.hair_type",array('div'=>'col-sm-3 col-md-3','label'=>'Preferred hair/coat type')); ?>
	<?= $this->Form->input("{$this->Form->defaultModel}.preference.color_preference",array('div'=>'col-sm-3 col-md-3','size'=>8)); ?>
	<?= $this->Form->input("{$this->Form->defaultModel}.preference.age_range",array('div'=>'col-sm-3 col-md-3','size'=>8)); ?>
	<?= $this->Form->input("{$this->Form->defaultModel}.preference.special_medical_needs_ok",array('div'=>'col-md-6','required'=>true,'options'=>$this->Form->yesno,'label'=>'Are you willing to accept an animal with special medical needs?')); ?>
	<?= $this->Form->input("{$this->Form->defaultModel}.preference.extra_attention_ok",array('div'=>'col-md-6','required'=>true,'options'=>$this->Form->yesno,'label'=>'Are you willing to accept an animal with a history of neglect/abuse who needs extra love and attention?')); ?>
	<?= $this->Form->input("{$this->Form->defaultModel}.preference.behavioral_problems_ok",array('div'=>'col-md-6','required'=>true,'options'=>$this->Form->yesno,'label'=>'Are you willing to accept an animal with behavioral problems who requires special training?')); ?>
	<?= $this->Form->input("{$this->Form->defaultModel}.preference.waiting_period",array('div'=>'col-md-6','label'=>'How long are you willing to wait for the ideal animal?')); ?>
</div>
