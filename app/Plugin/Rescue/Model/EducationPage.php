<?
App::uses("RescueAppModel", "Rescue.Model");
class EducationPage extends RescueAppModel
{
	var $actsAs  = array(
		'Sluggable.Sluggable',
		'Sortable.Sortable'
	);

	var $hasMany = array(
		#'Subpage'=>array('class'=>'Rescue.EducationPage')

	);

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto'),
	);



}
