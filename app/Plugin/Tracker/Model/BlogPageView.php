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
class BlogPageView extends AppModel {
	var $actsAs = array('Tracker.PageViewStat');
	var $order = 'BlogPageView.id DESC';

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
		'BlogVisit' => array(
			'className' => 'BlogVisit',
			'foreignKey' => 'blog_visit_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MarketingVisitorBlacklist'=>array(
			'foreignKey'=>false,
			'conditions'=>"BlogPageView.ip LIKE MarketingVisitorBlacklist.ip", # AND MarketingVisitorBlacklist.id IS NULL"

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
	/*
	var $hasMany = array(
		'Previous'=>array(
			'className'=>'BlogPageView',
			'foreignKey'=>false,
			'conditions'=>"Previous.session_id = BlogPageView.session_id AND Previous.id < BlogPageView.id",
			'order'=>'Previous.id DESC',
			'limit'=>1
		),
		'Next'=>array(
			'className'=>'BlogPageView',
			'foreignKey'=>false,
			'conditions'=>"Previous.session_id = BlogPageView.session_id AND Previous.id > BlogPageView.id",
			'order'=>'Previous.id ASC',
			'limit'=>1
		),
	);
	*/

	# We need to put id IS NULL into WHERE clause, above only does ON, which'll fail.
	function beforeFind($query)
	{
		if(!is_array($query['conditions'])) { $query['conditions'] = array($query['conditions']); }
		if($this->recursive > 0 && (!isset($query['recursive']) || $query['recursive'] > 0))
		{
			$query['conditions']['MarketingVisitorBlacklist.id'] = null;
		}
		return $query;
	}

}
