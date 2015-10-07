<?php
App::uses('AppModel', 'Model');
/**
 * SitePageView Model
 *
 * @property Site $Site
 * @property SiteVisit $SiteVisit
 * @property Session $Session
 * @property Page $Page
 */
class SitePageView extends AppModel {
	var $actsAs = array('Tracker.PageViewStat');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'refinternal' => array(
			'boolean' => array(
				'rule' => array('boolean'),
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
		'SiteVisit' => array(
			'className' => 'SiteVisit',
			'foreignKey' => 'site_visit_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		/*
		'Session' => array(
			'className' => 'Session',
			'foreignKey' => 'session_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Page' => array(
			'className' => 'Page',
			'foreignKey' => 'page_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
		*/
	);
}
