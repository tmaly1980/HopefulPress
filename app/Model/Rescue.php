<?
class Rescue extends AppModel
{
	var  $displayField = 'title';

	var $belongsTo = array(
		'Owner'=>array('className'=>'User','foreignKey'=>'user_id'),
		'PagePhoto'=>array('className'=>'PagePhotos.PagePhoto','foreignKey'=>'page_photo_id'),
		'AboutPhoto'=>array('className'=>'PagePhotos.PagePhoto','foreignKey'=>'about_photo_id'),
		'RescueLogo'=>array('foreignKey'=>'rescue_logo_id'),
	);

	var $hasMany = array(
		'RescueSpecialization'
	);

	var $validate = array(
		'hostname'=>array(
			'unique'=>array(
				'rule'=>'isUnique',
				'required'=>'create',
				'on'=>'create',
				'message'=>'That website address is already taken'
			),
			'alphanumeric'=>array(
				'rule'=>'/^[a-z0-9-]+$/i',
				'message'=>'Only letters, numbers and dashes'
			)

		),

	);

}
