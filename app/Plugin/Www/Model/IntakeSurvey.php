<?
App::uses("WwwAppModel", "Www.Model");
class IntakeSurvey extends WwwAppModel
{
	var $actsAs = array(
		'Core.CommaSeparated'
	);

	var $order = 'IntakeSurvey.created DESC';

	var $belongsTo = array(
		'LogoFile'=>array('className'=>'Www.IntakeSurveyFile','foreignKey'=>'logo_id'),
		'SampleFosterFormFile'=>array('className'=>'Www.IntakeSurveyFile','foreignKey'=>'sample_foster_form_file_id'),
		'SampleAdoptionFormFile'=>array('className'=>'Www.IntakeSurveyFile','foreignKey'=>'sample_adoption_form_file_id'),
		'SampleVolunteerFormFile'=>array('className'=>'Www.IntakeSurveyFile','foreignKey'=>'sample_volunteer_form_file_id'),

	);

}
