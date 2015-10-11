<?
App::uses("RescueAppModel", "Rescue.Model");
class AdoptionOverview extends RescueAppModel
{
	var $actsAs  = array(
		'Singleton.Singleton',
	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);

	/* No relation  because no field connecting...
	var $hasMany = array(
		'Page'=>array('className'=>'Rescue.AdoptionPage','order'=>'AdoptionPage.ix IS NULL, AdoptionPage.ix ASC,AdoptionPage.id ASC'),
		'Download'=>array('className'=>'Rescue.AdoptionDownload'),#,'order'=>'AdoptionDownload.ix IS NULL, AdoptionPage.ix ASC,AdoptionPage.id ASC'),
		'Faq'=>array('className'=>'Rescue.AdoptionFaq','order'=>'AdoptionFaq.ix IS NULL, AdoptionFaq.ix ASC,AdoptionFaq.id ASC'),
	);
	*/

	var $default_title = 'Adoption';
}
