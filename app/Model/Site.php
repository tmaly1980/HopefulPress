<?php
App::uses('AppModel', 'Model');
/**
 * Site Model
 *
 * @property User $User
 * @property User $User
 */
class Site extends AppModel {
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


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Owner' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
 	/* WHY???
	public $hasMany = array(
		'Users' => array(
			'className' => 'User',
			'foreignKey' => 'site_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	*/


}
