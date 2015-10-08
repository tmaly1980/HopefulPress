<?
class Rescue extends AppModel
{
	var  $displayField = 'title';

	var $belongsTo = array(
		'PagePhoto'=>array('foreignKey'=>'page_photo_id'),
		'RescueLogo'=>array('foreignKey'=>'rescue_logo_id'),
	);

	var $hasMany = array(
		'RescueSpecialization'
	);

}
