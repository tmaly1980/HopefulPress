<?
App::uses("UserCore", "UserCore.Model");
class Volunteer extends UserCore
{
	var $tablePrefix = "rescue_";
	var $actsAs  = array(
		# Which works?
		'Core.JsonColumn'=>array('fields'=>array('data','home_details')), # Do this first.
		'Core.CommaSeparated',
	);
	var $order = "Volunteer.status ASC";

	# DROPDOWNS
	var $statuses = array('Applied'=>'Applied','Active'=>'Active','Inactive'=>'Inactive','Rejected'=>'Rejected');#Received','Pending','Accepted','Denied');
	#var $statuses = array('Received','Pending','Accepted','Denied');
	#IGNORED
	###############

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
		#'Adoptable'=>array('className'=>'Rescue.Adoptable','foreignKey'=>'adoptable_id')
	);

	/* No relation  because no field connecting...
	var $hasMany = array(
		'Page'=>array('className'=>'Rescue.VolunteerPage','order'=>'VolunteerPage.ix IS NULL, VolunteerPage.ix ASC,VolunteerPage.id ASC'),
		'Download'=>array('className'=>'Rescue.VolunteerDownload'),#,'order'=>'VolunteerDownload.ix IS NULL, VolunteerPage.ix ASC,VolunteerPage.id ASC'),
		'Faq'=>array('className'=>'Rescue.VolunteerFaq','order'=>'VolunteerFaq.ix IS NULL, VolunteerFaq.ix ASC,VolunteerFaq.id ASC'),
	);
	*/

	function beforeValidate($options=array())
	{
		$this->validate['email']['isUnique']['message'] = "An application has already been submitted with that email.";
		return parent::beforeValidate();
	}


}
