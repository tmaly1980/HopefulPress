<?
class FosterPageIndex extends AppModel
{
	var $actsAs  = array(
		'Singleton.Singleton'=>'rescue_id',
	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);

	var $default_title = 'Foster';
}
