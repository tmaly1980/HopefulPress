<?
class RescueVolunteer extends AppModel # Application for specific rescue.
{
	var $actsAs  = array(
		# Which works?
		'Core.JsonColumn'=>array('fields'=>array('data','home_details')), # Do this first.
		'Core.CommaSeparated',
	);

	var $belongsTo = array(
		'Rescue',
		'User',
		'Volunteer',
	);

	/*
	function beforeValidate($options=array())
	{
		$this->validate['email']['isUnique']['message'] = "An application has already been submitted with that email.";
		return parent::beforeValidate();
	}
	*/


}
