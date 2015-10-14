<?
class Volunteer extends AppModel # PROFILE/APPLICATION
{
	var $actsAs  = array(
		# Which works?
		# IGNORED ON belongsTo, handled  internally below.
		#'Core.JsonColumn'=>array('fields'=>array('data','home_details')), # Do this first.
		#'Core.CommaSeparated',
	);
	#var $order = "Volunteer.status ASC";

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
		'User',
		#'Adoptable'=>array('className'=>'Rescue.Adoptable','foreignKey'=>'adoptable_id')
	);

	var $virtualFields = array(
		'full_name'=>"CONCAT(%ALIAS%.first_name, ' ', %ALIAS%.last_name)"
	);

	var $hasMany = array(
		'RescueVolunteer', # Hopefully on a rescue, this will filtr to just to THAT rescue's record.
	);

	/*
	function beforeValidate($options=array())
	{
		$this->validate['email']['isUnique']['message'] = "An application has already been submitted with that email.";
		return parent::beforeValidate();
	}
	*/

	# JSON behavior / afterFind firing  is useless on associated models
	function afterFind($results,$primary=false)
	{
		return $this->jsonDecode($results, array('data','home_details'));
	}
	function beforeSave($options=array())
	{
		 $this->jsonEncode(array('data','home_details'));
		 $this->commaJoin();
		 return true;
	}


}
