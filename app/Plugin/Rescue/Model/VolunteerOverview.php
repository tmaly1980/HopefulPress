<?
App::uses("RescueAppModel", "Rescue.Model");
class VolunteerOverview extends RescueAppModel
{
	var $actsAs  = array(
		'Singleton.Singleton',
	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);

	/* No relation  because no field connecting...
	var $hasMany = array(
		'Page'=>array('className'=>'Rescue.VolunteerPage','order'=>'VolunteerPage.ix IS NULL, VolunteerPage.ix ASC,VolunteerPage.id ASC'),
		'Download'=>array('className'=>'Rescue.VolunteerDownload'),#,'order'=>'VolunteerDownload.ix IS NULL, VolunteerPage.ix ASC,VolunteerPage.id ASC'),
		'Faq'=>array('className'=>'Rescue.VolunteerFaq','order'=>'VolunteerFaq.ix IS NULL, VolunteerFaq.ix ASC,VolunteerFaq.id ASC'),
	);
	*/

	var $default_title = 'Volunteer';
}
