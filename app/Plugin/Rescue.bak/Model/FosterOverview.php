<?
App::uses("RescueAppModel", "Rescue.Model");
class FosterOverview extends RescueAppModel
{
	var $actsAs  = array(
		'Singleton.Singleton',
	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);

	/* No relation  because no field connecting...
	var $hasMany = array(
		'Page'=>array('className'=>'Rescue.FosterPage','order'=>'FosterPage.ix IS NULL, FosterPage.ix ASC,FosterPage.id ASC'),
		'Download'=>array('className'=>'Rescue.FosterDownload'),#,'order'=>'FosterDownload.ix IS NULL, FosterPage.ix ASC,FosterPage.id ASC'),
		'Faq'=>array('className'=>'Rescue.FosterFaq','order'=>'FosterFaq.ix IS NULL, FosterFaq.ix ASC,FosterFaq.id ASC'),
	);
	*/

	var $default_title = 'Foster Information';
}
