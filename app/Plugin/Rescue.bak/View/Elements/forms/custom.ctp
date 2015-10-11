<? if(!empty($adoptionForm['AdoptionForm']['custom_fields'])) { ?>
	<h3><?= !empty($adoptionForm['AdoptionForm']['custom_title']) ? $adoptionForm['AdoptionForm']['custom_title'] : "Other Questions" ?></h3>
	<div class='row inline-checkbox'>
	<? foreach($adoptionForm['AdoptionForm']['custom_fields'] as $cf) { 
		$fieldLabel = $cf['field_question'];
		$fieldType = $cf['field_type'];
		$fieldName = preg_replace("/[^a-z0-9_-]+/i", "_",$fieldLabel);
		$fieldRequired = $cf['field_required'];
		$fieldOptionsList = !empty($cf['field_options'])? preg_split("/\n/", preg_replace("/\r?\n/", "\n", $cf['field_options'])): null;
		$fieldOptions = !empty($fieldOptionsList) ? array_combine($fieldOptionsList,$fieldOptionsList):null;
		if($fieldType == 'yesno')
		{
			$fieldType = 'select';
			$fieldOptions = ($fieldRequired?$this->Form->yesno:$this->Form->yesnoblank);
		}
		$empty = ($fieldType == 'select' && !$fieldRequired) ? " - " : null;
	?>
		<?= $this->Form->input("{$this->Form->defaultModel}.custom.$fieldName",array('type'=>$fieldType,'empty'=>$empty,'div'=>'col-md-6','label'=>$fieldLabel,'required'=>$fieldRequired,'options'=>$fieldOptions)); ?>
	<? } ?>
	</div>
<? } ?>
