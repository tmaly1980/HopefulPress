<?
App::uses("RescueAppModel", "Rescue.Model");
class AdoptionStory extends AppModel
{
	var $actsAs  = array(
		'Core.Dateable'=>array(
			'fields'=>array('created'), # Not birthdate since we format that relatively...
		),
	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto','foreignKey'=>'page_photo_id'),
		'Adoptable'=>array('className'=>'Adoptable','foreignKey'=>'adoptable_id'),
	);
	
	var $hasMany = array(
		#'Photo'=>array('className'=>'Rescue.AdoptablePhoto','order'=>'Photo.ix IS NULL, Photo.ix ASC,Photo.id ASC'),
		#'Video'=>array('className'=>'Rescue.AdoptableVideo','foreignKey'=>'adoptable_id'),
	);
}
