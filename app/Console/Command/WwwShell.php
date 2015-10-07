<?

App::import("Core", "Security");
App::import("Core", "HttpSocket");
App::import("Routing", "Router");

App::uses("HpShell", "Console/Command");

# move to hp_shell to consolidate loading, etc.
class WwwShell extends HpShell
{
	var $components = array('Session','Multisite.Multisite','Example');
	var $uses = array('Site','User','Blog.Post','BlogPageView','MarketingPageView');

	# When removing site, will remove all records from these tables also - when reset applies to whole example site
	var $related = array(
		'User',
		'SiteDesign',
		'Homepage',
		'AboutPage',
		'ContactPage',
		'Contact',
		'Page',
		'Project.Project',
		'Members.MemberPage',
		'NewsPost',
		'Event',
		'EventContact',
		'EventLocation',
		'PhotoAlbum',
		'Photo',
		'PagePhoto',
		'Link',
		'LinkCategory',
		'Download',
		'SitePageView',
		'MarketingVisit',
		'SiteShare'
	);
	var $singletons = array('homepages','about_pages','contact_pages');

	function initialize()
	{
		Configure::write("prefix", "admin"); # To fake some queries to see full (unpulished) dataset.

		#error_log("PARAMS=".print_r($this->params,true));
		parent::initialize();
		foreach($this->related as $model)
		{
			$this->loadModel($model);
		}
	}


	# FLAGS W/O PARAMS SHOULD GO AT THE END....
	function help()
	{
		?>
Usage: cake www stats [TOTAL]

<?
	$this->hr();
	}

	##############################################################################
	# CONTENT RETRIEVAL 
	###########################c


	# Command flag should do either reset+create, reset, or append
	# Command flags: -r = reset (+ add), -d = delete (no add), else, just add (w/o del)

	function reset($model, $cond = array(), $default_reset = false) # this does reset for reset-only or reset+append
	{
		# Default reset applies to singletons; if we call a function, the only
		# choice is to wipe it out first.
		$params = $this->params;
		# Make local copy so 'reset' doesnt stick

		if(!empty($default_reset))
		{
			$params['r'] = 1;
		}


		#if(!empty($this->params['a'])) { return; } # Append-only mode.
		if(empty($params['r']) && empty($params['d'])) { return false; } 
		# Neither reset nor delete mode
		
		$site_id = Configure::read("site_id");
		$this->out("RESET, @ SITE_ID=$site_id");

		if(empty($cond)) { $cond = array(); }

		if(!empty($site_id))
		{
			$this->out("Resetting $model (".print_r($cond,true).") for site_id $site_id");

			$cond = array_merge($cond, array("{$model}.site_id"=>$site_id));
			$this->{$model}->deleteAll($cond);
			$this->out("DONE DELETE ".print_r($cond,true));
		} 

		if(!empty($params['d'])) { 
			$this->out("DELETING ONLY. EXITING.");
		}

		return !empty($params['d']); # if delete only, caller should exit
	}

	function save($model, $data)
	{
		if(!empty($this->params['r'])) # reset only, not appending.
		{
			return;
		}

		$this->hr();

		$this->out("SAVING $model=".print_r($data,true));

		$site_id = $this->Example->get_site_id();
		$this->{$model}->id = null;
		$this->{$model}->save(array($model=>$data));
		return $this->{$model}->id;
	}



	###########################################################
	# MODES:
	# Command flags: -r = reset (+ add), -d = delete (no add), else, just add (w/o del)
	# deletes only happen for a single model, not related (hasMany) models
	#
	# -from START_DATE
	# -to FINISH_DATE 
	# (for random date generation, back dating of news, events, etc)

	function _stats($total = null)
	{
		$total = !empty($total) ? $total : (rand(0,4) ? rand(10,50) : rand(100,500)); 

		for($visit = 0; $visit < $total; $visit++)
		{
			$session_id = $this->Example->random_session_id();
			# Blog post view
			$post = $this->Post->find('first',array('order'=>'rand()'));
			$url = "/blog/posts/view/{$post['Post']['id']}";
			$pageView = $this->_pageView($url, $session_id);
			$this->save("BlogPageView", $pageView);
			
			$factor = rand(2,7);

			if(rand(0,10) > $factor) # Marketing site view
			{
				$url = "/www/static/view/home";
				$pageView = $this->_pageView($url, $session_id, $pageView['created']);
				$this->save("MarketingPageView", $pageView);
				if(rand(0,10) > $factor) # Signup page view
				{
					$url = "/sites/signup";
					$pageView = $this->_pageView($url, $session_id, $pageView['created']);
					$this->save("MarketingPageView", $pageView);

					# DONE
					# Signups & upgrades done manually in example builder
				}
			}

		}
	}

	function _pageView($urlString, $session_id, $from = '3 month ago')
	{
		$created = $this->Example->random_date($from);
		$url = Router::parse($urlString);
		$pageView = array(
			'session_id'=>$session_id,
			'url'=>$urlString,
			'controller'=>$url['controller'],
			'ip'=>$this->Example->random_ip(),
			'action'=>$url['action'],
			'page_id'=>!empty($url['pass'])?$url['pass'][0]:null,
			'created'=>$created,
			'title'=>$urlString,
			'fake'=>1, # So we can remove later if needed, w/o wiping legit stuff.
			# No need for refkeywords, refdomain, etc...
		);
		return $pageView;

	}

}

