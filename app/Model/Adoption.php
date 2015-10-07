<?
App::uses("RescueAppModel", "Rescue.Model");
class Adoption extends AppModel
{
	var $actsAs  = array(
		'Core.JsonColumn'=>array('fields'=>array('data','pet_ownership_history','home_details','care_and_responsibility','preference','references')),
	);
	var $order = "Adoption.status ASC";

	# DROPDOWNS
	var $statuses = array('Received','Pending','Accepted','Deferred','Denied');
	###############

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
		'Adoptable'=>array('className'=>'Adoptable','foreignKey'=>'adoptable_id')
	);

	/* No relation  because no field connecting...
	var $hasMany = array(
		'Page'=>array('className'=>'Rescue.AdoptionPage','order'=>'AdoptionPage.ix IS NULL, AdoptionPage.ix ASC,AdoptionPage.id ASC'),
		'Download'=>array('className'=>'Rescue.AdoptionDownload'),#,'order'=>'AdoptionDownload.ix IS NULL, AdoptionPage.ix ASC,AdoptionPage.id ASC'),
		'Faq'=>array('className'=>'Rescue.AdoptionFaq','order'=>'AdoptionFaq.ix IS NULL, AdoptionFaq.ix ASC,AdoptionFaq.id ASC'),
	);
	*/

	var $default_title = 'Adoption Application';

	var $default_introduction = 'Please fill out the following form to the best of your abilities. We will get back to you as soon as possible.';

 	public $virtualFields = array(
		'full_name'=>"CONCAT(%ALIAS%.first_name, ' ', %ALIAS%.last_name)",
	);

}
