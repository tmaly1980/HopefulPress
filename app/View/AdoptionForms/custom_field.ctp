		<? $fieldType = !empty($this->request->data['AdoptionForm']['custom_fields'][$i]['field_type']) ? $this->request->data['AdoptionForm']['custom_fields'][$i]['field_type'] : null; ?>
		<div class='col-xs-1'>
		<?= $this->Html->remove("","javascript:void(0)",array('class'=>'FieldDelete')); ?>
		</div>
		<?= $this->Form->input((isset($i)?"AdoptionForm.custom_fields.$i":"_").".field_question",array('label'=>false,'placeholder'=>'Question goes here...','class'=>'FieldQuestion','div'=>'col-xs-11')); ?>
		<?= $this->Form->input((isset($i)?"AdoptionForm.custom_fields.$i":"_").".field_type",array('label'=>false,'div'=>'col-sm-8 col-md-8','class'=>'FieldType','options'=>array('text'=>'Single-line text','textarea'=>'Multi-line text','yesno'=>'Yes/No','select'=>'One of the following (dropdown)','checkbox'=>'One or more of the following (checkboxes)'))); ?>
		<?= $this->Form->input((isset($i)?"AdoptionForm.custom_fields.$i":"_").".field_required",array('div'=>'col-xs-4 col-md-4','class'=>'FieldRequired','type'=>'checkbox','label'=>'Required to answer')); ?>
		<div class='clear'></div>
		<div class='FieldOptions' style="<?= !in_array($fieldType,array('select','checkbox'))?"display:none;":"" ?>">
			<?= $this->Form->input((isset($i)?"AdoptionForm.custom_fields.$i":"_").".field_options",array('type'=>'textarea', 'label'=>'Possible choices (one per line)')); ?>
		</div>

