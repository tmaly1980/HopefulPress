<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 * @property Site $Site
 * @property User $User
 * @property Photo $Photo
 * @property EventLocation $EventLocation
 * @property EventContact $EventContact
 */
class Event extends AppModel {
	var $virtualFields = array(
		'future'=>"( DATE(Event.end_date) >= DATE(NOW()) OR DATE(Event.start_date) >= DATE(NOW()) )",
		'recent'=>"( DATE(Event.start_date) >= DATE_SUB(NOW(), INTERVAL 1 MONTH) AND DATE(Event.start_date) < DATE(NOW()) )", # Last month but not any more.
	);
	var $actsAs = array(
		'Sluggable.Sluggable',
		#'Projectable',
		#'SoftDeletable',
		#'Updatable',
		'Core.Dateable'=>array( # Date conversion.
			'fields'=>array('start_date','end_date'),
			'format'=>"M j, Y", # Jan 13, 2002
		));

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
		'User',
		#'MemberPages.Member',
		'PagePhoto' => array(
			'className' => 'PagePhoto',
			'foreignKey' => 'page_photo_id',
		),
		'EventLocation' => array(
			'className' => 'EventLocation',
			'foreignKey' => 'event_location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EventContact' => array(
			'className' => 'EventContact',
			'foreignKey' => 'event_contact_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
