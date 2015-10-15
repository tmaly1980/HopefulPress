<?
class AdoptionForm extends AppModel
{
	var $actsAs  = array(
		'Singleton.Singleton'=>'rescue_id',
		'Core.JsonColumn'=>array('fields'=>array('custom_fields')),
	);

	var $belongsTo = array(
		#'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
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

}
