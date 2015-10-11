<?
class VolunteerPageIndex extends AppModel
{
	var $actsAs  = array(
		'Singleton.Singleton',
	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);

	var $default_title = 'Volunteer';
}
