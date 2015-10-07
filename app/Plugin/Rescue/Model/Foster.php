<?
App::uses("UserCore", "UserCore.Model");
class Foster extends UserCore
{
	var $tablePrefix = "rescue_";
	
	var $actsAs  = array(
		'Core.JsonColumn'=>array('fields'=>array('data','home_details')), # Do this first.
		'Core.CommaSeparated',
	);
	var $order = "Foster.status ASC";

	# DROPDOWNS
	var $statuses = array('Applied'=>'Applied','Active'=>'Active','Inactive'=>'Inactive','Rejected'=>'Rejected');#Received','Pending','Accepted','Denied');
	#IGNORED
	###############

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
		#'Adoptable'=>array('className'=>'Rescue.Adoptable','foreignKey'=>'adoptable_id')
	);

	/* No relation  because no field connecting...
	var $hasMany = array(
		'Page'=>array('className'=>'Rescue.FosterPage','order'=>'FosterPage.ix IS NULL, FosterPage.ix ASC,FosterPage.id ASC'),
		'Download'=>array('className'=>'Rescue.FosterDownload'),#,'order'=>'FosterDownload.ix IS NULL, FosterPage.ix ASC,FosterPage.id ASC'),
		'Faq'=>array('className'=>'Rescue.FosterFaq','order'=>'FosterFaq.ix IS NULL, FosterFaq.ix ASC,FosterFaq.id ASC'),
	);
	*/

	function beforeValidate($options=array())
	{
		$this->validate['email']['isUnique']['message'] = "An application has already been submitted with that email.";
		return parent::beforeValidate();
	}

}
