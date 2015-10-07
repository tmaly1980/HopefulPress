<?php
App::uses('AppModel', 'Model');
/**
 * SiteVisit Model
 *
 * @property Site $Site
 * @property Session $Session
 * @property StartPage $StartPage
 * @property EndPage $EndPage
 * @property SitePageView $SitePageView
 */
class BlogVisit extends AppModel {
	var $actsAs = array('Tracker.Geoip','Tracker.VisitStat'); # Auto assign info...

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
		'MarketingVisitorBlacklist'=>array(
			'foreignKey'=>false,
			'conditions'=>"BlogVisit.ip = MarketingVisitorBlacklist.ip"
		),
		/*
		'Session' => array(
			'className' => 'Session',
			'foreignKey' => 'session_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'StartPage' => array(
			'className' => 'StartPage',
			'foreignKey' => 'start_page_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EndPage' => array(
			'className' => 'EndPage',
			'foreignKey' => 'end_page_id',
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
		'BlogPageView' => array(
			'className' => 'BlogPageView',
			'foreignKey' => 'blog_visit_id',
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

}
