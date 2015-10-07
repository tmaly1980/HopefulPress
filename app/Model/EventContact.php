<?php
App::uses('AppModel', 'Model');
/**
 * EventContact Model
 *
 * @property Site $Site
 * @property User $User
 * @property Event $Event
 */
class EventContact extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'site_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
	/*
		'Site' => array(
			'className' => 'Site',
			'foreignKey' => 'site_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	*/
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_contact_id',
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

	function beforeFind($query) # Filter out blank names.
	{
		if(empty($query['conditions'])) { $query['conditions'] = array(); }
		if(!is_array($query['conditions'])) { $query['conditions'] = array($query['conditions']); }
		$query['conditions'][] = "{$this->alias}.name != '' AND {$this->alias}.name IS NOT NULL ";
		return $query;
	}

}
