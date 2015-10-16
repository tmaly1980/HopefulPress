<?

App::uses("HostInfo", "Core.Lib");

class TrackerComponent extends Component
{
	var $uses = array("Tracker.SiteVisit",'Tracker.SitePageView','Tracker.SiteShare','Tracker.MarketingVisit','Tracker.MarketingPageView','Tracker.BlogVisit','Tracker.BlogPageView','Tracker.BlogShare');
	var $components = array('Auth','Session');

	var $ip;
	var $browser;
	var $referer = null;
	var $referer_keywords = null;
	var $url;
	var $enabled = false;
	var $tracked = false; # Did sooner.

	var $internal_domains = array();

	var $visit_timeout = 30; # minutes
	
	var $prefix = 'Site'; # Site, Marketing, Blog ; #For model names. passed to track()

	var $controller = null;

	function startup(Controller $controller)
	{
		$this->controller = $controller;
		/*
		foreach($this->uses as $model)
		{
			$this->controller->loadModel($model);
			list($plugin,$modelClass) = pluginSplit($model);
			$this->{$modelClass} = $this->controller->{$modelClass};
		}
		*/
		$this->request = $this->controller->request;

	}


	function internal_referer($host)
	{
		$default_domains = HostInfo::default_domains();
		foreach($default_domains as $domain)
		{
			if(preg_match("@http[s]?://[^/]*$domain/@", $host))
			{
				return false;
			}
		}
		return true;
	}

	function get_referer($referer)#, $external = false)
	{
		$host = preg_replace("/\?.*/", "", $referer);
		#if($external && $this->internal_referer($host)) { return null; }
		return $host;
	}
	function get_query_string($url)
	{
		$query_string = preg_replace("/.*\?/", "", $url);
		return $query_string;
	}

	function get_keywords($qstring)#, $external = false)
	{
		parse_str($qstring, $qs);
		$search = null;

       		if(!empty($qs['q']))
        	{
                	$search = $qs['q'];
        	} else if (!empty($qs['p'])) {
                	$search = $qs['p'];
        	} else if (!empty($qs['query'])) {
                	$search = $qs['query'];
        	}
		return $search;
	}


	/*
	# Events (internal)
	# - blog post visitors / views
	# - marketing site visitors / views
	# - signup page views
	# - signups (event + date)
	# - active sites (date)
	# - paid users (upgraded - date)
	function event($event)
	{ # NOT IMPLEMENTED/USED
		if($this->isBot()) { return false; }
		if($this->isBanned()) { return false; }

		list($prefix,$event) = pluginSplit($event); # Marketing.

		$sid = $this->get_session_id();

		$this_site = $_SERVER['HTTP_HOST']; # Could be their custom domain...

		$this->TrackerEvent->create();
		$this->TrackerEvent->save(array('TrackerEvent'=>array(
			'ip'=>$this->get_ip(),
			'session_id'=>$sid,
			'browser'=>$_SERVER['HTTP_USER_AGENT'],
			'prefix'=>$prefix,
			'event'=>$event,
			'url'=>$_SERVER['REQUEST_URI']
		)));
	}
	*/

	function logout()
	{
	}

	function loadModels($prefix)
	{
		$this->controller->loadModel("{$prefix}Visit");
		$this->{"{$prefix}Visit"} = $this->controller->{"{$prefix}Visit"};
		$this->controller->loadModel("{$prefix}PageView");
		$this->{"{$prefix}PageView"} = $this->controller->{"{$prefix}PageView"};
		#$this->controller->loadModel("{$prefix}Share");
		#$this->{"{$prefix}Share"} = $this->controller->{"{$prefix}Share"};

	}

	# Tracking is now done manually.... per each index() and view()

	function track($prefix = 'Site')
	{
		#error_log("CALLED TRACK! $prefix");
		if($this->isBanned()) { return false; }

		$this->loadModels($prefix);


		$this->prefix = $prefix;
		# Never track admin pages...
		if(!empty($this->controller->request->params['admin']))
		{
			#error_log("NOT TRACKING, /admin");
			return;
		}

		# Never track 'sites' plugin...
		if($this->controller->request->params['plugin'] == 'sites')
		{
			#error_log("NOT TRACKING, /sites");
			return;
		}

		# And never track people who are logged in to admin before...
		# (those with 'User' accounts...)
		# (if we ever need accounts that still track, we should use a different model)
		if($this->Session->read("Auth.User")) { return; }

		# EXCLUDE IF INTERNAL COOKIE SET (browser)
		if(!empty($_COOKIE['admin'])) { return; }

		######################################################

		#error_log("TRACK IS ENABLED");

		$this->enabled = true;
		# This gets delayed until after view renders, so we can get title from the view.
	}

	function clear()
	{
		# Remove all entries for this IP address.
		$ip = $this->get_ip();
		$this->{$this->prefix."Visit"}->deleteAll(array("{$this->prefix}Visit.ip"=>$ip));
		$this->{$this->prefix."PageView"}->deleteAll(array("{$this->prefix}PageView.ip"=>$ip));
		error_log("ERASING {$this->prefix} $ip");
	}

	# XXX if I make changes to bot list, it SHOULD remove existing records from DB....
	# XXX ? maybe have a URL to go to "hp.com/manager/dashboard/cleanup" ?
	function isBanned()
	{
		if($this->isBot()) # Kept in a hardcoded file... I can manually enter and go to cleanup url to clean out records
		{
			error_log("IS_BOT!!!!");
			return true;
		}
		if($this->isSpamReferer())
		{
			error_log("IS_SPAMy!!!!");
			return true;
		}
		# Other IP related stuff...
		# Can banned IP's get written to file???
		# XXX need functions to read/write/append/refresh IP list file....

		return false;
	}

	function beforeRedirect(Controller $controller, $url, $status=null,$exit=true) #  If page redirects, but still want to be tracked....
	{
		$this->shutdown($controller); # Call explicitly since skipped.
	}

	function shutdown(Controller $controller) # Tracking is put into shutdown so we can get title from view AFTER rendering is complete...
	{

		/*if(empty($this->controller->View)) # View class only gets declared in render() !!!!!
		{
			error_log("CANT TRACK USER, SOMETHING IS WRONG WITH THE PAGE");
			return false;
		}
		# Might not have, ie for redirects
		*/
		if($this->isBanned()) { return false; }
		# Need to filter out bots and stuff..

		#error_log("TRACCKER ENABLED={$this->enabled}");

		if(!$this->enabled) { return; }

		#if($this->Session->read("internal")) { 
		#	$this->clear();
		#	return; 
		#}

		# else was enabled somewhere in time.

		#error_log("TRACKING ({$this->prefix}), {$_SERVER['HTTP_USER_AGENT']} @ {$_SERVER['REMOTE_ADDR']} FOR {$_SERVER['HTTP_HOST']}");

		$visit = $this->track_visit();
		#error_log("VIS=".print_r($visit,true));
		if(!empty($visit))
		{
			$this->track_page_view($visit);
		}

		if(!empty($this->controller->ab_view) && !empty($this->controller->ab_variant))
		{
			$this->controller->abview_save(); # We call since only we know visit_id (during shutdown, AFTER render)
		}

		$this->tracked = true;  # Don't do a second time.

	}

	function get_session_id()
	{
		return $this->controller->Session->id();
	}

	function hide_session() # Called upon login of a user.
	{
		error_log("HID_SESS={$this->prefix}");
		$session_id = $this->get_session_id();
		# Upon login to admin/manager, we need to 
		# cleanup stats for this user (based on session ID)

		$this->{"{$this->prefix}Visit"}->deleteAll(" {$this->prefix}Visit.session_id = '$session_id' ");
		$this->{"{$this->prefix}PageView"}->deleteAll(" {$this->prefix}PageView.session_id = '$session_id' ");
		if($this->{"{$this->prefix}Share"})
		{
			$this->{"{$this->prefix}Share"}->deleteAll(" {$this->prefix}Share.session_id = '$session_id' ");
		}
	}

	# Hiding internal users is already taken care of...
	function track_visit()
	{
		#error_log("TRACK VISIT");
		#error_log("USER=".print_r($_SERVER,true));

		$thisclass = get_class($this);
		$sid = $this->get_session_id();
		# Look for existing visit. within 30 minutes.
		$visit_id = $this->controller->Session->read("{$this->prefix}.visit_id");
		$existing = !empty($visit_id) ? $this->{"{$this->prefix}Visit"}->read(null, $visit_id) : 
			$this->{"{$this->prefix}Visit"}->find('first', array('conditions'=>
				array('session_id'=>$sid,"end >= DATE_SUB(NOW(), INTERVAL {$this->visit_timeout} MINUTE)")));
			# Needs to be relative to END (last page viewed), so gradual use of site doesnt start a new session every 30 mins.
		$existing_id = !empty($existing["{$this->prefix}Visit"]) ? $existing["{$this->prefix}Visit"][$this->{"{$this->prefix}Visit"}->primaryKey] : null; # only read() stores id

		#error_log("SID=$sid, EXIST=".print_r($existing,true));

		/*if($this->prefix = 'Marketing')
		{
			$this->MarketingVisit->sqlDump();
			exit(0);
		}*/

		#error_log("EXISTING=".print_r($existing,true));
		if(!empty($existing))
		{
			# Update page_views.
			$this->{"{$this->prefix}Visit"}->id = $existing_id;
			$this->{"{$this->prefix}Visit"}->saveField('end', date('Y-m-d H:i:s'));
			$this->{"{$this->prefix}Visit"}->saveField("page_views", $existing["{$this->prefix}Visit"]['page_views']+1);
			# Also update end of session...

			#error_log("UPDATING VISIT...$existing_id");

			$this->controller->Session->write("{$this->prefix}.visit_id", $existing["{$this->prefix}Visit"]['id']);

		} else { # New
			$urlparts = preg_split("/[?]/", $_SERVER['REQUEST_URI']);
			$url = $urlparts[0];
			$query_string = !empty($urlparts[1]) ? $urlparts[1] : null;

			$referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

			$this_site = $_SERVER['HTTP_HOST']; # Could be their custom domain...

			$refurl = parse_url($referer);
			$refdomain = !empty($refurl['host']) ? $refurl['host'] : null;
			$refqs = !empty($refurl['query']) ? $refurl['query'] : null;
			$refpath = !empty($refurl['path']) ? $refurl['path'] : null;

			$domains = HostInfo::default_domains();
			$domains[] = preg_replace("/^www[.]/", "", $_SERVER['HTTP_HOST']);

			# May need better way to mark as internal, ie www, blog, etc...
			# And treating customer.wpw.com as NOT internal...
			# ie mark in DB (config file) what is "internal" - and filter out manually.
			# As things may change over time when I add new subdomains
			# may be worth querying by checking config setting instead of flag.

			$sitelist = join("|", $domains);
			$refinternal = preg_match("/^($sitelist)$/", $refdomain) ? 1 : 0;

			$campaigncode = $campaignsubcode = null;
			if(!empty($this->request->query['ref'])) { $campaigncode = $this->request->query['ref']; }
			if(!empty($this->request->query['ref_id'])) { $campaignsubcode = $this->request->query['ref_id']; }

			if(!empty($this->request->params['ref'])) { $campaigncode = $this->request->params['ref']; }
			if(!empty($this->request->params['ref_id'])) { $campaignsubcode = $this->request->params['ref_id'] ; }

			error_log("NEW VISIT {$this->prefix}...");


			$this->{"{$this->prefix}Visit"}->create();
			$this->{"{$this->prefix}Visit"}->save(array(
				'ip'=>$this->get_ip(),
				'session_id'=>$sid,
				'browser'=>$_SERVER['HTTP_USER_AGENT'],
				'url'=>$url,
				'query_string'=>$query_string,
				'refdomain'=>$refdomain,
				'campaign_code'=>$campaigncode,
				'campaign_subcode'=>$campaignsubcode,
				'referer'=>$referer,
				'refqs'=>$refqs,
				'refpath'=>$refpath,
				'refinternal'=>$refinternal,
				'refkeywords'=>$this->get_keywords($refqs),
				'page_views'=>1,
				'start'=>date('Y-m-d H:i:s'),
				'end'=>date('Y-m-d H:i:s'),
			));
			$existing = $this->{"{$this->prefix}Visit"}->read();
			$this->controller->Session->write("{$this->prefix}.visit_id", $this->{"{$this->prefix}Visit"}->id);

			#error_log("TRACKED VISIT=".$this->{"{$this->prefix}Visit"}->id);
		}
		#error_log("SAVING VISIT($thisclass)@{$this->prefix}=".$this->controller->Session->read("{$this->prefix}.visit_id"));
		return $existing;
	}

	function get_ip()
	{
		# May spoof IP, ie fake tracker...
		$ip_address = !empty($_SERVER['HTTP_FAKE_ADDRESS']) ? $_SERVER['HTTP_FAKE_ADDRESS'] : $_SERVER['REMOTE_ADDR'];

		error_log("GET_IP=$ip_address");

		return $ip_address;
	}

	function track_page_view($visit = null) # Probably includes downloads, if proxied thru view()
	{
		$thisclass = get_class($this);
		if(empty($visit))
		{
			$visit_id = $this->controller->Session->read("{$this->prefix}.visit_id");
			#error_log("LOOKIP VISIT=$visit_id");
			$visit = $this->{"{$this->prefix}Visit"}->read(null, $visit_id);
		} else {
			$visit_id = !empty($visit["{$this->prefix}Visit"]['id']) ? $visit["{$this->prefix}Visit"]['id'] : null;
			#error_log("VISIT_ID=$visit_id");
		}

		$sid = $this->get_session_id();

		$urlparts = preg_split("/[?]/", $_SERVER['REQUEST_URI']);
		$url = $urlparts[0];

		# MAY need to simplify URL, ie so singletons do NOT record 'view/id' in URL...
		$urlparsed = Router::parse($url);
		if($this->controller->{$this->controller->modelClass}->Behaviors->attached("Singleton"))
		{
			$url = Router::url(array('controller'=>$urlparsed['controller']));
		} 



		$query_string = !empty($urlparts[1]) ? $urlparts[1] : null;
		$referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

		$refurl = parse_url($referer);
		$refdomain = !empty($refurl['host']) ? $refurl['host'] : null;
		$refqs = !empty($refurl['query']) ? $refurl['query'] : null;
		$refpath = !empty($refurl['path']) ? $refurl['path'] : null;

		$this_site = $_SERVER['HTTP_HOST']; # Could be their custom domain...
		$refinternal = preg_match("/(malysoft.com|hopefulpress.com|$this_site)/", $refdomain);
		$sitelist = join("|", array("malysoft.com","hopefulpress.com",$_SERVER['HTTP_HOST']));
		$refinternal = preg_match("/($sitelist)/", $refdomain) ? 1 : 0;

		$campaigncode = !empty($this->request->query['ref']) ? $this->request->query['ref'] : null;
		$campaignsubcode = !empty($this->request->query['ref_id']) ? $this->request->query['ref_id'] : null;

		$title = null;
		if(!empty($this->controller->View))
		{
			$title = $this->controller->View->fetch("page_title");
			if(empty($title))
			{
				$title = $this->controller->View->getVar("title_for_layout");
			}
		}
		#error_log("TRACKING TITLE FOR $url = $title");
		#error_log(print_r($this->controller->View->getVars(),true));

		$visit_key = Inflector::underscore("{$this->prefix}VisitId");
		#error_log("VK=$visit_key");

		$this->{"{$this->prefix}PageView"}->create();
		$this->{"{$this->prefix}PageView"}->save(array(
			$visit_key=>$visit_id,
			'ip'=>$this->get_ip(),
			'session_id'=>$sid,
			'url'=>$url,
			'query_string'=>$query_string,
			'refdomain'=>$refdomain,
			'refpath'=>$refpath,
			'refinternal'=>$refinternal,
			'refqs'=>$refqs,
			'campaign_code'=>$campaigncode,
			'campaign_subcode'=>$campaignsubcode,
			'title'=>$title,
			'refkeywords'=>$this->get_keywords($refqs),
			'controller'=>$this->controller->request->params['controller'],
			'action'=>$this->controller->request->params['action'],
			'page_id'=>!empty($this->controller->request->params['pass']) ? $this->controller->request->params['pass'][0] : null,
		));

		$page_view_id = $this->{"{$this->prefix}PageView"}->id;

		# Update visit to reflect page view, if first or last.
		$this->{"{$this->prefix}Visit"}->id = $visit_id;
		$this->{"{$this->prefix}Visit"}->saveField('end', date('Y-m-d H:i:s'));
		$this->{"{$this->prefix}Visit"}->saveField('end_page_id', $page_view_id);
		if(empty($visit["{$this->prefix}Visit"]['start_page_id']))
		{
			$this->{"{$this->prefix}Visit"}->saveField('start_page_id', $page_view_id);
		}
		#error_log("TRACKED PAGEVIEW=".$page_view_id);

	}

	function track_share($social_site, $data = array())
	{
		if($this->isBot()) { return false; }

		if(empty($data)) { 
			if(!empty($this->request->data['Email']))
			{
				$data = $this->request->data['Email'];
			} else if(!empty($this->request->query)) { 
				$data = $this->request->query; 
			}
		}
		$thisclass = get_class($this);
		if(empty($visit))
		{
			$visit_id = $this->controller->Session->read("{$this->prefix}.visit_id");
			$visit = $this->{"{$this->prefix}Visit"}->read(null, $visit_id);
		} else {
			$visit_id = !empty($visit["{$this->prefix}Visit"]['id']) ? $visit["{$this->prefix}Visit"]['id'] : null;
		}

		#error_log("TRACK_SHARE=".print_r($data,true));

		$page_url = !empty($data['page_url']) ? 
			$data['page_url'] : null;

		$page_title = !empty($data['page_title']) ? 
			$data['page_title'] : null;

		if(empty($page_url)) { return; } # Irrelevant.

		$sid = $this->get_session_id();

		# XXX TODO geoip, someday....

		$this->{"{$this->prefix}Share"}->create();
		$this->{"{$this->prefix}Share"}->save(array(
			#'visit_id'=>$visit_id,
			'social_site'=>$social_site,
			'ip_address'=>$this->get_ip(),
			'session_id'=>$sid,
			'page_url'=>urldecode($page_url),
			'title'=>urldecode($page_title),
		));

		return $share_id = $this->{"{$this->prefix}Share"}->id;
	}

	function isSpamReferer()
	{
		$ref = !empty($_SERVER['HTTP_REFERER']) ? strtolower($_SERVER['HTTP_REFERER']) : null;
		if(empty($ref)) { return false; }

		Configure::load("Tracker.spamReferers");
		$this->spamReferers = Configure::read("Tracker.spamReferers");

		#error_log("REF=$ref");

    		foreach($this->spamReferers as $spam) {
			#error_log("REF=$ref, SPAM+$spam, STRPOS=".stripos($ref,$spam));
        		if ( stripos($ref, $spam) !== false ) return true;
    		}


		return false;
	}

	function isBot()
	{
		$agent = !empty($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : null;
		if(empty($agent)) { return true; }

		Configure::load("Tracker.bots");
		$this->bots = Configure::read("Tracker.bots");

    		foreach($this->bots as $bot) {
        		//If the spider text is found in the current user agent, then return true
        		if ( stripos($agent, $bot) !== false ) return true;
    		}

		return false;
	}

	function geoip($ip)
	{
		$rc = App::import("Vendor", "Tracker.geoip");
		$rc = App::import("Vendor", "Tracker.geoipcity");
		$gi = geoip_open(APP."/Plugin/Tracker/Vendor/GeoLiteCity.dat", GEOIP_STANDARD);
		$result = geoip_record_by_addr($gi, $ip);
		geoip_close($gi);
		$geoip = get_object_vars($result);
		return $geoip;
	}

}
?>
