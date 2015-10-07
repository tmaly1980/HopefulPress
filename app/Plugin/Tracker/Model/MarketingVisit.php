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
class MarketingVisit extends AppModel {
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
			'conditions'=>"MarketingVisit.ip = MarketingVisitorBlacklist.ip"
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
		'MarketingPageView' => array(
			'className' => 'MarketingPageView',
			'foreignKey' => 'marketing_visit_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'MarketingPageView.created, MarketingPageView.id',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	function beforeFind($query) // Always hide if on blacklist.
	{
		if(!is_array($query['conditions'])) { $query['conditions'] = array($query['conditions']); }

		$ips = $this->MarketingVisitorBlacklist->fields('ip'); // skip null
		#error_log("IPS=".print_r($ips,true));
		$blacklist = array_filter($ips);
		$query['conditions']['MarketingVisit.ip NOT'] = $blacklist;
		$query['conditions']['OR']['MarketingVisit.country NOT'] = array('China','Japan');
		$query['conditions']['OR'][] = 'MarketingVisit.country IS NULL';

		#
		return $query;
	}

	function campaignCodes()
	{
		return $this->fields('campaign_code', array('campaign_code IS NOT NULL'));
	}

	function campaignVisits($days = 30)
	{
		$visits = array();

		$campaign_visits = $this->find('list', array('fields'=>array("id", "campaign_code"), 'conditions'=>"created > DATE_SUB(NOW(), INTERVAL $days DAY)"));
		foreach($campaign_visits as $visit_id=>$campaign_code)
		{
			if(empty($visits[$campaign_code])) {
				$visits[$campaign_code] = array(
					'visits'=>0,
					'page_views'=>0
				);
			}
			$visits[$campaign_code]['visits']++;
			$page_views = $this->MarketingPageView->count(array('marketing_visit_id'=>$visit_id));
			$visits[$campaign_code]['page_views'] += $page_views;
		}
		return $visits;
	}

}
