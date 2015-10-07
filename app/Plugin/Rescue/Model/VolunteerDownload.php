<?
App::uses("RescueAppModel", "Rescue.Model");
class VolunteerDownload extends RescueAppModel
{
	var $actsAs  = array(
		#'Core.Singleton'
		'Sortable.Sortable',
		'Core.Upload'
	);
	var $order = 'VolunteerDownload.ix IS NULL, VolunteerDownload.ix ASC, VolunteerDownload.modified DESC, VolunteerDownload.created DESC';

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);
	
	var $hasMany = array(
		#'VolunteerPage'=>array('className'=>'Rescue.VolunteerPage','order'=>'VolunteerPage.ix IS NULL, VolunteerPage.ix ASC,VolunteerPage.id ASC'),
		#'VolunteerDownload'=>array('className'=>'Rescue.VolunteerDownload'),#,'order'=>'VolunteerDownload.ix IS NULL, VolunteerPage.ix ASC,VolunteerPage.id ASC'),
		#'VolunteerFaq'=>array('className'=>'Rescue.VolunteerFaq','order'=>'VolunteerFaq.ix IS NULL, VolunteerFaq.ix ASC,VolunteerFaq.id ASC'),
	);
}
