<h3>References</h3>
		<div id=''>
			<fieldset>
				<legend>Please list three references (veterinarian, relatives, neighbors, friends, co-workers)</legend>
				<div class='row'>
					<?= $this->Form->input("Adoption.references.reference_1.first_name", array('required'=>1,'label'=>'First name','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_1.last_name", array('required'=>1,'label'=>'Last name','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_1.relationship", array('required'=>1,'label'=>'Relationship','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_1.phone", array('required'=>1,'label'=>'Phone','div'=>'col-md-3')); ?>
				</div>
				<div class='row'>
					<?= $this->Form->input("Adoption.references.reference_2.first_name", array('required'=>1,'label'=>'First name','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_2.last_name", array('required'=>1,'label'=>'Last name','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_2.relationship", array('required'=>1,'label'=>'Relationship','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_2.phone", array('required'=>1,'label'=>'Phone','div'=>'col-md-3')); ?>
				</div>
				<div class='row'>
					<?= $this->Form->input("Adoption.references.reference_3.first_name", array('required'=>1,'label'=>'First name','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_3.last_name", array('required'=>1,'label'=>'Last name','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_3.relationship", array('required'=>1,'label'=>'Relationship','div'=>'col-md-3')); ?>
					<?= $this->Form->input("Adoption.references.reference_3.phone", array('required'=>1,'label'=>'Phone','div'=>'col-md-3')); ?>
				</div>
			</fieldset>
		</div>

