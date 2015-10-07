<?
class StatsController extends WwwAppController
{
	var $uses = array('Site','Rescue.Adoptable','Rescue.DonationOverview','Rescue.FosterOverview','Rescue.VolunteerOverview',
		'Rescue.Foster','Rescue.Volunteer','Rescue.Adoption',
		'Newsletter.MailchimpCredential', 'Stripe.StripeCredential','Paypal.PaypalCredential','User',
		'MarketingVisit','MarketingPageView',
		'Core.ABResult'
	);

	function index()
	{
		$this->set("referrals", $this->_referrals());
		$this->set("abresults", $this->_abresults());
		$this->set("pages", $this->_page_stats());
		$this->set("signupCount", $this->_recent_signups());
		$this->set("sites", $this->_site_stats());
	}

	function _abresults($days = 30)
	{
		Configure::load("ab"); # CTA's defined

		# Only show tests for variants whose files still exist... ie ACTIVE tests...

		$rows = $this->ABResult->find('all',array('recursive'=>1,'conditions'=>array("ABResult.created > DATE_SUB(NOW(), INTERVAL $days DAY)")));
		# Cannot group since each may have different session_id, we need to see exact per-person behavior...

		$results = array();
		foreach($rows as $row)
		{
			$visit_id = $row['ABResult']['marketing_visit_id']; # If we don't have this, we don't know who they are....
			if(empty($visit_id)  || empty($row['MarketingVisit']['id'])) {  # Could be bogus user or we removed their visit.
				error_log("AB_RESULT_FAILURE: NEVER SAVED VISIT_ID!!!");
				continue;
			}
			$pluginCC = Inflector::camelize($plugin  = $row['ABResult']['plugin']);
			$controllerCC = Inflector::camelize($controller = $row['ABResult']['controller']);
			$view = $row['ABResult']['view'];
			$variant = $row['ABResult']['variant'];
			$created = $row['ABResult']['created'];

			$variantPath = APP.(!empty($pluginCC)?"/Plugin/$pluginCC":"")."/View/$controllerCC/$view.$variant.ctp";
			# If we WANTED to look at a full history of tests, we can show each variant with date ranges (first and last record found)
			# For now, just hide them since we probably know which is superior.
			if(!file_exists($variantPath)) # Obsolete variant. Might have tried a new set since then.
			{
				error_log("AB_RESULT_OBSOLETE ($controllerCC/$view/$variant): file gone");
				continue;
			}

			$path  = ($plugin?"$plugin/":"")."$controller/$view";

			if(empty($results[$path])) { 
				$results[$path] = array();
			}
			if(empty($results[$path][$variant])) { 
				$results[$path][$variant] = array('cta'=>array(),'calls'=>0);
			}
			$results[$path][$variant]['calls']++; // So we can calculate conversion rate (since call counts may vary, ie not 50%).


			# Now do test for calls to action....
			$CTAs = Configure::read("ABAction.$path");

			error_log("CTAS ($path)=".print_r($CTAs,true));

			if(!empty($CTAs['page'])) # Check one/more pages also in visit, AFTER this point...
			{
				error_log("PAGECHECK=".print_r($CTAs['page'],true));
				if(!is_array($CTAs['page'])) { $CTAs['page'] = array($CTAs['page']); }
				foreach($CTAs['page'] as $page)
				{
					if(empty($results[$path][$variant]['cta'][$page])) { 
						$results[$path][$variant]['cta'][$page] = 0;
					}
					$results[$path][$variant]['cta'][$page] += $this->MarketingPageView->count(array('url'=>$page,'marketing_visit_id'=>$visit_id,'created >'=>$created));
				}
			}
			if(!empty($CTAs['model'])) # Check one/more model records with visit_id stored....
			{
				if(!is_array($CTAs['model'])) { $CTAs['model'] = array($CTAs['model']); }
				foreach($CTAs['model'] as $model)
				{
					if(empty($results[$path][$variant]['cta'][$model])) { 
						$results[$path][$variant]['cta'][$model] = 0;
					}
					$this->loadModel($model);
					list($plugin,$modelClass) = pluginSplit($model);
					error_log("LOADING=$model");
					$results[$path][$variant]['cta'][$model] += $this->$modelClass->count(array('marketing_visit_id'=>$visit_id,'created >'=>$created));
				}
			}
		}

		return $results;

	}

	# View individual visits and delete/ban if bogus....
	function visits($date=null) # Might be just a specific day.
	{
		if(!empty($date)) # May get "Sep 5", to convert.
		{
			$date = date("Y-m-d", strtotime($date));
			$conditions = array("start BETWEEN '$date 00:00:00' AND '$date 23:59:59'");
			$this->set("when",date("M j Y", strtotime($date)));
		} else if (isset($this->request->query['refdomain'])) { # Specific referer/source (ie bogus brides4sale.ru) - or no referer at all!
			$conditions = array("refdomain"=>$this->request->query['refdomain']);
			$this->set("when",$this->request->query['refdomain']);
		} else {
			$days = 30;
			$conditions = array("start  > DATE_SUB(NOW(), INTERVAL $days DAY)");
			$this->set("days", $days);
		}

		# Hide illegitimate visitors...
		# Now, either the referer is set or the end time is different than the start-time...
		$conditions[] = '(DATE_ADD(start, INTERVAL 3 SECOND) < end OR (refdomain != "" AND refdomain NOT LIKE "%hopefulpress.com%"))';
		# Sometimes they're a second off....

		$visits = $this->MarketingVisit->find('all',array('conditions'=>$conditions,
			'order'=>"MarketingVisit.id DESC"));

		$this->set("visits", $visits);
	}


	function _recent_signups($days = 14)
	{
		return $this->Site->count(array("created > DATE_SUB(NOW(), INTERVAL $days DAY)"));
	}

	function _page_stats($days = 14) # Marketing site effectiveness, with calls to action, etc.
	{
		$urls = array("/","/pages/about","/pages/features","/pages/demo","/contact","/pages/signup","/signup");

		$pages = array();

		foreach($urls as $url)
		{
			$page_views = $this->MarketingPageView->find('all',array(
				'fields'=>array('id','session_id','url'),
				'recursive'=>1,
				'group'=>'marketing_visit_id', # Don't count multiple times if same user/bot did it.
				'conditions'=>array('MarketingPageView.url'=>$url,
					"MarketingPageView.created > DATE_SUB(NOW(), INTERVAL $days DAY)",
					'(DATE_ADD(MarketingVisit.start, INTERVAL 3 SECOND) < MarketingVisit.end OR (MarketingVisit.refdomain != "" AND MarketingVisit.refdomain NOT LIKE "%hopefulpress.com%"))', # Hide empty/bogus visits.
					'MarketingVisit.id IS NOT NULL'), # Visit hasn't been deleted.
			));

			# Next pages/counts
			$nexts = array();
			$view_count = 0;

			foreach($page_views as $view)
			{
				$sid = $view['MarketingPageView']['session_id'];
				$vid = $view['MarketingPageView']['id'];
				$view_url = $view['MarketingPageView']['url'];
				$next_url = $this->MarketingPageView->field('url',array('session_id'=>$sid,"id > $vid"),"id ASC"); # Get just next one for session.

				if($next_url == $view_url) { continue; } # Ignore hits to same page, (ie / => /, probably bogus)

				if(empty($nexts[$next_url])) { $nexts[$next_url] = 0; } # If empty string, we found nothing and they exited!
				$nexts[$next_url]++;
				$view_count++;
			}

			$page = array(
				'views'=>$view_count,
				'next'=>$nexts,
				# Exits are calculated
			);
			$pages[$url] = $page;
		}

		return $pages;
	}

	# Two sets of graphs? marketing site flow + signup / site use flow

	function chart() # Funnel
	{
		$data = array();

		# Visits, signup page views (per session), signups, upgrades
		$data['Visits'] = $this->_chart_data('MarketingVisit','start',array('(DATE_ADD(start, INTERVAL 3 SECOND) < end OR (refdomain != "" AND refdomain NOT LIKE "%hopefulpress.com%"))')); # Hide empty hits.
		$data['Pricing'] = $this->_chart_data('MarketingPageView','created',array("url IN ('/pages/signup')"),"session_id");
		$data['Signup Pages'] = $this->_chart_data('MarketingPageView','created',array("url IN ('/signup')"),"session_id");
		$data['Signups'] = $this->_chart_data('Site','created');
		$data['Upgrades'] = $this->_chart_data('Site','upgraded',array('plan != ""'));

		#$this->Site->sqlDump();

		$this->Json->render($data);
	}

	function _chart_data($model, $date_field, $conditions = array(), $distinct = null, $days = 14)
	{
		$conditions[] = "$date_field > DATE_SUB(NOW(), INTERVAL $days DAY)";
		$results = $this->$model->find('all',array(
			'conditions'=>$conditions,
			'fields'=>array("DATE($date_field) AS date",(!empty($distinct)?"COUNT(DISTINCT $distinct) AS count": "COUNT(*) AS count")),
			'group'=>"date",
			'recursive'=>-1,
			'order'=>'count DESC')
		);
		#echo "DATA($model/$date_field)=".print_r($results,true);

		$values = Set::combine($results, "{n}.0.date", "{n}.0.count");

		#echo("VALES=($model, $date_field)=".print_r($values,true));

		$data = array();

		$subtotal = 0;

		# Calculate a SUM based on the dates.
		# At least until I get enough traffic that every day has conversions/etc. Until then, some days may have more of a further metric, since it takes more than a day to decide.

		for($i = $days; $i >= 0; $i--)
		{
			$date = date("Y-m-d", strtotime("$i days ago"));

			# Try just what happens in day. Today's successes. Don't want data distorted based on past.
			if(!empty($values[$date]))
			{
				$data[$date] = $values[$date];
			} else {
				$data[$date] = 0;
			}

			# Subtotal/sum approach
			#if(!empty($values[$date]))
			#{
				# Subtotal version...
				#$subtotal += $values[$date];
			#}
			#$data[$label] = $subtotal; # Pretty label.
		}

		$xy_data = array();

		# Convert to xy pairs.... and fancy dates
		foreach($data as $raw_date=>$value)
		{
			$date = date("M j", strtotime($raw_date));
			$xy_data[] = array($date,$value);#,$raw_date); # Raw_date is thrown out when combining...
		}

		return $xy_data;
	}

	function _referrals_during($start="today",$end="today")
	{
		$start_date = date("Y-m-d 00:00:00", strtotime($start));
		$end_date = date("Y-m-d 23:59:59", strtotime($end));
		#echo "BETWEEN ($start) $start_date AND ($end) $end_date; \n";
		$refs = $this->MarketingVisit->find('all', array(
			'conditions'=>array("start BETWEEN '$start_date' AND '$end_date'"),
			'fields'=>array('refdomain','COUNT(*) AS count'),
			'recursive'=>-1,
			'group'=>'refdomain','order'=>'count DESC'));

		return Set::combine($refs, "{n}.MarketingVisit.refdomain", "{n}.0.count");
	}

	# Get all referral sources, for each day, then combine to compare growth
	function _referrals($days  = 30)
	{
		# Today
		$today = $this->_referrals_during("today");

		# Yesterday
		$yesterday = $this->_referrals_during("yesterday","yesterday");

		# This week
		$thisweek = $this->_referrals_during("-7 day");

		# Last week
		$lastweek = $this->_referrals_during("-2 week", "-1 week -1 day");

		# This month
		$thismonth = $this->_referrals_during("-1 month");
		
		# Last month
		$lastmonth = $this->_referrals_during("-2 month","-1 month -1 day");


		# Now key by referer
		$blankref = array('day'=>0,'yesterday'=>0,'week'=>0,'lastweek'=>0,'month'=>0,'lastmonth'=>0);
		$refs = array();

		foreach($today as $ref=>$count) { 
			if(empty($refs[$ref])) { $refs[$ref] = $blankref; }
			$refs[$ref]['day'] = $count;
		}
		foreach($yesterday as $ref=>$count) { 
			if(empty($refs[$ref])) { $refs[$ref] = $blankref; }
			$refs[$ref]['yesterday'] = $count;
		}
		foreach($thisweek as $ref=>$count) { 
			if(empty($refs[$ref])) { $refs[$ref] = $blankref; }
			$refs[$ref]['week'] = $count;
		}
		foreach($lastweek as $ref=>$count) { 
			if(empty($refs[$ref])) { $refs[$ref] = $blankref; }
			$refs[$ref]['lastweek'] = $count;
		}
		foreach($thismonth as $ref=>$count) { 
			if(empty($refs[$ref])) { $refs[$ref] = $blankref; }
			$refs[$ref]['month'] = $count;
		}
		foreach($lastmonth as $ref=>$count) { 
			if(empty($refs[$ref])) { $refs[$ref] = $blankref; }
			$refs[$ref]['lastmonth'] = $count;
		}

		# Sort by month, week, day
		uasort($refs, function($a,$b) {
			if($a['month'] < $b['month']) { return -1; }
			if($a['month'] > $b['month']) { return 1; }

			# Monthly same, compare by week
			if($a['week'] < $b['week']) { return -1; }
			if($a['week'] > $b['week']) { return 1; }

			# Weekly same, compare by day
			if($a['day'] < $b['day']) { return -1; }
			if($a['day'] > $b['day']) { return 1; }

			return 0;  # Same.
		});

		return array_reverse($refs);
	}

	# XXX graph stats funnel

	function _site_stats()
	{
		$sites = $this->Site->find('all',array('disabled'=>null));

		$stats = array();

		foreach($sites  as $site)
		{
			$site_id = $site['Site']['id'];
			$site['Site']['user_count'] = $this->User->count(array('site_id'=>$site_id));
			$site['Site']['foster_count'] = $this->Foster->count(array('site_id'=>$site_id)); # Applied is good, form is being used!
			$site['Site']['volunteer_count'] = $this->Volunteer->count(array('site_id'=>$site_id)); # Applied is good, form is being used!

			$site['Site']['adoptable_count'] = $this->Adoptable->count(array('site_id'=>$site_id));
			$site['Site']['adoption_count'] = $this->Adoption->count(array('site_id'=>$site_id)); # People using form!
			$site['Site']['mailing_list_enabled'] = $this->MailchimpCredential->count(array('site_id'=>$site_id));
			$site['Site']['donation_enabled'] = 
				$this->StripeCredential->count(array('site_id'=>$site_id)) ||
				$this->PaypalCredential->count(array('site_id'=>$site_id));

			$site['Site']['volunteer_enabled'] = $this->VolunteerOverview->count(array('site_id'=>$site_id,'disabled'=>0));
			$site['Site']['foster_enabled'] = $this->FosterOverview->count(array('site_id'=>$site_id,'disabled'=>0));

			$site['Site']['Owner'] = $site['Owner']; # Copy.
			########################################
			$stats[] = $site['Site'];
		}

		return $stats;
	}

}
