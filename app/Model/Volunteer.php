<?
class Volunteer extends AppModel # PROFILE/APPLICATION
{
	var $actsAs  = array(
		# Which works?
		'Core.JsonColumn'=>array('fields'=>array('data','home_details')), # Do this first.
		'Core.CommaSeparated',
	);
	#var $order = "Volunteer.status ASC";

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
		'User',
		#'Adoptable'=>array('className'=>'Rescue.Adoptable','foreignKey'=>'adoptable_id')
	);

	/*
	function beforeValidate($options=array())
	{
		$this->validate['email']['isUnique']['message'] = "An application has already been submitted with that email.";
		return parent::beforeValidate();
	}
	*/


}
