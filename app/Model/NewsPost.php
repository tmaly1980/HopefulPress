<?php
App::uses('AppModel', 'Model');
class NewsPost extends AppModel {
	public $order = 'NewsPost.created DESC';

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
		#'User',
		#'MemberPages.Member',
		'PagePhoto' => array(
			'foreignKey' => 'page_photo_id',
		)
	);
	public $hasOne = array(
		'DraftNewsPost'=>array(
			'className'=>'NewsPost',
			'foreignKey'=>'draft_id'
		)
	);

	var $actsAs = array(
		#'Projectable',
		#'SoftDeletable',
		#'Updatable',
		#######'Publishable.Publishable',
		'Sluggable.Sluggable',
		'Core.Dateable'=>array('fields'=>array('created'))
	);
}
