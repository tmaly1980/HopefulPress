<?php
App::uses('RescueAppModel', 'Rescue.Model');
class SuccessStoryPhoto extends AppModel {
	var $actsAs = array('Core.Upload');

	public $validate = array(
	);

	public $belongsTo = array(
		'Adoptable' => array(
			'className' => 'Adoptable',
			'foreignKey' => 'adoptable_id',
		)
	);

}
