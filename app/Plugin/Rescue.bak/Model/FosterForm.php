<?
App::uses("RescueAppModel", "Rescue.Model");
class FosterForm extends RescueAppModel
{
	var $actsAs  = array(
		'Singleton.Singleton',
	);

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);

	/* No relation  because no field connecting...
	var $hasMany = array(
		'Page'=>array('className'=>'Rescue.FosterPage','order'=>'FosterPage.ix IS NULL, FosterPage.ix ASC,FosterPage.id ASC'),
		'Download'=>array('className'=>'Rescue.FosterDownload'),#,'order'=>'FosterDownload.ix IS NULL, FosterPage.ix ASC,FosterPage.id ASC'),
		'Faq'=>array('className'=>'Rescue.FosterFaq','order'=>'FosterFaq.ix IS NULL, FosterFaq.ix ASC,FosterFaq.id ASC'),
	);
	*/

	var $default_title = 'Foster Application';

	var $default_introduction = 'Please fill out the following form to the best of your abilities. We will get back to you as soon as possible.';
}
