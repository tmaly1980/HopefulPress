<?
App::uses("RescueAppModel", "Rescue.Model");
class FosterDownload extends RescueAppModel
{
	var $actsAs  = array(
		#'Core.Singleton'
		'Sortable.Sortable',
		'Core.Upload'
	);
	var $order = 'FosterDownload.ix IS NULL, FosterDownload.ix ASC, FosterDownload.modified DESC, FosterDownload.created DESC';

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);
	
	var $hasMany = array(
		#'FosterPage'=>array('className'=>'Rescue.FosterPage','order'=>'FosterPage.ix IS NULL, FosterPage.ix ASC,FosterPage.id ASC'),
		#'FosterDownload'=>array('className'=>'Rescue.FosterDownload'),#,'order'=>'FosterDownload.ix IS NULL, FosterPage.ix ASC,FosterPage.id ASC'),
		#'FosterFaq'=>array('className'=>'Rescue.FosterFaq','order'=>'FosterFaq.ix IS NULL, FosterFaq.ix ASC,FosterFaq.id ASC'),
	);
}
