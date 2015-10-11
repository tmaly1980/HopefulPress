<?
App::uses("RescueAppModel", "Rescue.Model");
class AdoptionFaq extends RescueAppModel
{
	var $actsAs  = array(
		'Sortable.Sortable'
		#'Core.Singleton'
	);
	var $order = 'AdoptionFaq.ix IS NULL, AdoptionFaq.ix ASC';

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);
	
	var $hasMany = array(
		#'AdoptionPage'=>array('className'=>'Rescue.AdoptionPage','order'=>'AdoptionPage.ix IS NULL, AdoptionPage.ix ASC,AdoptionPage.id ASC'),
		#'AdoptionDownload'=>array('className'=>'Rescue.AdoptionDownload'),#,'order'=>'AdoptionDownload.ix IS NULL, AdoptionPage.ix ASC,AdoptionPage.id ASC'),
		#'AdoptionFaq'=>array('className'=>'Rescue.AdoptionFaq','order'=>'AdoptionFaq.ix IS NULL, AdoptionFaq.ix ASC,AdoptionFaq.id ASC'),
	);
}
