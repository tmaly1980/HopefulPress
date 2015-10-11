<?
class VolunteerPage extends AppModel
{
	var $actsAs  = array(
	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);
}
