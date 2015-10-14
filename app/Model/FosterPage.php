<?
class FosterPage extends AppModel
{
	var $actsAs  = array(
	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);
}
