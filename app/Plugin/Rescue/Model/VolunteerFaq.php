<?
App::uses("RescueAppModel", "Rescue.Model");
class VolunteerFaq extends RescueAppModel
{
	var $actsAs  = array(
		'Sortable.Sortable'
		#'Core.Singleton'
	);
	var $order = 'VolunteerFaq.ix IS NULL, VolunteerFaq.ix ASC';

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);
	
	var $hasMany = array(
		#'VolunteerPage'=>array('className'=>'Rescue.VolunteerPage','order'=>'VolunteerPage.ix IS NULL, VolunteerPage.ix ASC,VolunteerPage.id ASC'),
		#'VolunteerDownload'=>array('className'=>'Rescue.VolunteerDownload'),#,'order'=>'VolunteerDownload.ix IS NULL, VolunteerPage.ix ASC,VolunteerPage.id ASC'),
		#'VolunteerFaq'=>array('className'=>'Rescue.VolunteerFaq','order'=>'VolunteerFaq.ix IS NULL, VolunteerFaq.ix ASC,VolunteerFaq.id ASC'),
	);
}
