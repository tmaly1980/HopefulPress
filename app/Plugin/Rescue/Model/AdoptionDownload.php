<?
App::uses("RescueAppModel", "Rescue.Model");
class AdoptionDownload extends RescueAppModel
{
	var $actsAs  = array(
		#'Core.Singleton'
		'Sortable.Sortable',
		'Core.Upload'
	);
	var $order = 'AdoptionDownload.ix IS NULL, AdoptionDownload.ix ASC, AdoptionDownload.modified DESC, AdoptionDownload.created DESC';

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);
	
	var $hasMany = array(
		#'AdoptionPage'=>array('className'=>'Rescue.AdoptionPage','order'=>'AdoptionPage.ix IS NULL, AdoptionPage.ix ASC,AdoptionPage.id ASC'),
		#'AdoptionDownload'=>array('className'=>'Rescue.AdoptionDownload'),#,'order'=>'AdoptionDownload.ix IS NULL, AdoptionPage.ix ASC,AdoptionPage.id ASC'),
		#'AdoptionFaq'=>array('className'=>'Rescue.AdoptionFaq','order'=>'AdoptionFaq.ix IS NULL, AdoptionFaq.ix ASC,AdoptionFaq.id ASC'),
	);
}
