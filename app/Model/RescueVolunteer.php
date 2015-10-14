<?
class RescueVolunteer extends AppModel # Application for specific rescue.
{
	var $actsAs  = array(
		# Which works?
		# Manual decode below...
		#####'Core.JsonColumn'=>array('fields'=>array('data','home_details')), # Do this first.
		#'Core.CommaSeparated',
	);

	var $belongsTo = array(
		'Rescue',
		'User',
		'Volunteer',
	);

	var $statuses = array('Active','Active Offline','Applied','Inactive','Ignored');

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
