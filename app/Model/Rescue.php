<?
class Rescue extends AppModel
{
	var  $displayField = 'title';

	var $belongsTo = array(
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto','foreignKey'=>'page_photo_id'),
		'AboutPhoto'=>array('className'=>'PagePhotos.PagePhoto','foreignKey'=>'about_photo_id'),
		'RescueLogo'=>array('foreignKey'=>'rescue_logo_id'),
	);

	var $hasMany = array(
		'RescueSpecialization'
	);

}
