<?
App::uses("WwwAppController", "Www.Controller");
class DashboardController extends WwwAppController
{
	public $components = array("Tracker.Tracker");
	public $uses = array('Site','NewsPost','Event','Photo','Page','SiteDesign','SiteVisit','SitePageView','Blog.Post','BlogPageView','MarketingPageView');

	public $helpers = array('Chart.Chart');


	function manager_index()
	{
		$sites = $this->Site->findAll(array('internal'=>false));
		foreach($sites as &$site)
		{
			$sid = $site['Site']['id'];
			$siteDesign = $this->SiteDesign->first(array('SiteDesign.site_id'=>$sid));
			$site['SiteDesign'] = !empty($siteDesign) ? $siteDesign['SiteDesign'] : null;
			$site['userCount'] = $this->User->count(array('site_id'=>$sid,'id !='=>$site['Site']['user_id']));

			$site['newsCount'] = $this->NewsPost->count(array('site_id'=>$sid));
			$site['eventCount'] = $this->Event->count(array('site_id'=>$sid));
			$site['photoCount'] = $this->Photo->count(array('site_id'=>$sid));
			$site['pageCount'] = $this->Page->count(array('site_id'=>$sid));
			$site['lastLogin'] = $this->User->field("MAX(last_login)", array('site_id'=>$sid));

			$lastmonth = date('Y-m-d', strtotime('one month ago'));

			$site['pageViews'] = $this->SitePageView->count(array('site_id'=>$sid,'created >'=>$lastmonth));
			$site['visits'] = $this->SiteVisit->count(array('site_id'=>$sid,'created >'=>$lastmonth));

		}
		$this->set("sites", $sites);

	}

	function chart($type = 'funnel')
	{
		# ASSUME 1 month
		$start = date('Y-m-d',strtotime('1 month ago'));

		# XXX TODO to ensure correlation of one step to next, must filter by session_ids
		$this->BlogPageView->virtualFields['distinct_session_id'] = 'COUNT(DISTINCT session_id)';
		$this->BlogPageView->virtualFields['created_ymd'] = 'DATE(created)';
		$postViews = $this->BlogPageView->plotdates('created','distinct_session_id',array('start'=>$start,'datespan'=>'month','group'=>'created_ymd'),true);

		# May need to limit one entry per session_id
		$this->MarketingPageView->virtualFields['distinct_session_id'] = 'COUNT(DISTINCT session_id)';
		$this->MarketingPageView->virtualFields['created_ymd'] = 'DATE(created)';
		$marketingVisits = $this->MarketingPageView->plotdates('created','distinct_session_id',array('start'=>$start,'conditions'=>array('controller'=>'static'),'datespan'=>'month','group'=>'created_ymd'),true);


		$signupViews = $this->MarketingPageView->plotdates('created','distinct_session_id',array('start'=>$start,'conditions'=>array('controller'=>'sites','action'=>'signup'),'datespan'=>'month','group'=>'created_ymd'),true);


		$this->Site->virtualFields['count_id'] = 'COUNT(*)';
		$this->Site->virtualFields['created_ymd'] = 'DATE(created)';
		$this->Site->virtualFields['upgraded_ymd'] = 'DATE(upgraded)';

		$trials = $this->Site->plotdates('created','count_id',array('start'=>$start,'datespan'=>'month'), true);
		$paid = $this->Site->plotdates('created','count_id',array('start'=>$start,'conditions'=>array('upgraded IS NOT NULL'),'datespan'=>'month'), true);

		$owner_ids = $this->Site->fields("user_id");
		$this->User->virtualFields['count_id'] = 'COUNT(*)';
		$this->User->virtualFields['created_ymd'] = 'DATE(created)';
		$registrations = $this->User->plotdates('created','count_id',array('start'=>$start,'conditions'=>array('User.id'=>$owner_ids),'datespan'=>'month'), true);

		$data = array(
			'Blog Post Views'=>$postViews,
			'Marketing Site Visits'=>$marketingVisits,
			'Signup Page Views'=>$signupViews,
			'Free Trials'=>$trials,
			'Account Registrations'=>$registrations,
			'Paid Accounts'=>$paid,
		); # Most simple format...

		$this->Post->virtualFields['created_ymd'] = 'DATE(created)';
		$postDates = $this->Post->fields('created_ymd', array('created >='=>$start));

		$output = array(
			'data'=>$data,
			'opts'=>array(
				'events'=>$postDates
			)
		);

		return $this->Json->render($output);
	}

}
