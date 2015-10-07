<?

App::import("Core", "Security");
App::import("Core", "HttpSocket");
App::import("Routing", "Router");

App::uses("HpShell", "Console/Command");

# move to hp_shell to consolidate loading, etc.
class SiteShell extends HpShell
{
	var $components = array('Session','Multisite.Multisite','Example');
	var $uses = array('Site','User');

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
		'SiteVisit',
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
Usage: cake site [cmd] [args] [flags]

	example HOSTNAME "SITE TITLE"
	# Generates sql (and creates) an example site (free/internal) with lots of content...
	# Will clear data beforehand, from each table

	site HOSTNAME "SITE TITLE" # Create site + template
	remove_site HOSTNAME 
	users HOSTNAME
	homepage HOSTNAME "Page Title"
	about HOSTNAME
	contact HOSTNAME # Contact page + random contacts
	topics HOSTNAME
	projects HOSTNAME
	pages HOSTNAME
	news HOSTNAME
	events HOSTNAME
	photo_albums HOSTNAME
	links HOSTNAME
	downloads HOSTNAME
	videos HOSTNAME
	audio HOSTNAME
	visits HOSTNAME # Adds random page_views also
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

	function _example($hostname = null, $title = 'Example Site') # Creates an example site.
	# TODO break down into separate pieces, ie so can add more pages, more traffic, etc.
	# without wiping whole site out.
	{
		if(empty($hostname)) { return $this->help(); }

		$this->out("CREATING SITE $hostname '$title'");

		#
		# All sample files, content, photos, etc are stored in example/
		#
		# news, events, pages, homepage, about page, each will have a random photo.


		# Create site record.
		$this->_site($hostname, $title);
		
		# Users
		$this->_users($hostname);

		# Homepage
		$this->_homepage($hostname, "Welcome to $title");

		# About Page
		$this->_about($hostname);

		# Contact Page
		$this->_contact($hostname);

		# Topics & sub-pages
		$this->_topics($hostname);

		# Projects & sub-pages
		$this->_projects($hostname);

		# Top-level pages
		$this->_pages($hostname);

		###############################

		# News
		$this->_news($hostname);

		# Events (w/locations, contacts)
		$this->_events($hostname);

		# Photo alums + photos
		$this->_photo_albums($hostname);

		############################
		# listables: pages, categories (when possible), items 

		# links
		$this->_links($hostname);

		# files
		$this->_downloads($hostname);

		# videos
		#$this->_videos($hostname);

		# audio
		#$this->_audio($hostname);

		############################

		# Page views
		#$this->_page_views($hostname);
			# Add entries for every day (for a year), random from 10 to 500

		# page visits + referrals
		$this->_visits($hostname);

		# Members page & content.
		$this->_member_page($hostname);
	}

	function _sites($total = 10)
	{
		$this->out("Adding $total random sites (no content)");

		for($i = 0; $i < $total; $i++)
		{
			$n = rand(100000,99999999);
			$hostname = "site$n";
			$title = "Example Site $n";
			$this->_site($hostname, $title);
		}
	}

	function _site($hostname, $title = 'Example Site', $site_id = null)
	{
		$this->out("Creating/modifying site $hostname");

		if(!empty($site_id))
		{
			$this->site_id = $site_id;
			Configure::write("site_id", $this->site_id);
		} else {
			$site_id = $this->set_hostname($hostname,false);
		}
		# Don't remove site if existing. 
		#$site_id = $this->Example->get_site_id($hostname);

		if($count = $this->Site->count(array('Site.hostname'=>$hostname)))
		{
			$this->out("Site $hostname already exists... ($count)");
			return;
		}

		$this->hr();
		$this->out("Creating site $hostname");

		$created = $this->Example->random_date('3 months ago');

		$this->Site->create();
		$this->Site->save(array('Site'=>array(
			'id'=>$site_id,
			'hostname'=>$hostname,
			'title'=>$title,
			'internal'=>1,
			'created'=>$created,
			'upgraded'=>(rand(0,10) > 8 ? $this->Example->random_date($created) : null),
		)));

		$this->site_id = $this->Site->id;
		Configure::write("site_id", $this->site_id); # For models.

		# SiteDesign, w/logo, random theme
		$this->_site_design($hostname, $title);
	}

	function _remove_site($hostname) # And everything related. # GOOD
	{ # Not called unless explicit.
		foreach($this->related as $model)
		{
			list($plugin, $modelClass) = pluginSplit($model);
			$this->out("BINDING $modelClass...");
			$this->Site->bindModel(array('hasMany'=>array(
				$modelClass=>array(
					'className'=>$model,
					'exclusive'=>true,
					'dependent'=>true # Recursive delete!
				)
			)),false);
		}
		$this->out("Removing site $hostname and all content");
		#exit(0);
		$this->Site->deleteAll(array('Site.hostname'=>$hostname),true);
	}


	function _users($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		if($this->reset("User")) { return; } # reset only.

		$domain = $this->Example->domain($hostname);

		$total = !empty($total) ? $total : (rand(0,3) ? 0 : rand(3,15)); # Might not be any at all...

		for($i = 0; $i < $total; $i++)
		{
			$user = $this->Example->randomize("User");

			$this->save('User', $user);
			if(!$i)
			{
				$this->Site->saveField("user_id", $this->User->id); # Owner is first person.
			}
		}
	}

	function _site_design($hostname, $title = 'Example Site')
	{
		$this->set_hostname($hostname);
		if($this->reset("SiteDesign", null, true)) { return; } # Reset only.

		$this->out("Creating site design for $hostname");

		$logo_id = rand(0,5) > 2 ? $this->Example->random_photo("site_design/logos") : null;
		#$right_photo_id = rand(0,1) ? $this->Example->random_photo() : null;
		# XXX copy images to these folders XXX

		$lorem = $this->Example->lorem(45);
		$header_text_right = wordwrap($lorem, 15);
		$facebook = rand(0,1) ? "http://www.facebook.com/$hostname" : null;
		$twitter = rand(0,1) ? "http://www.twitter.com/$hostname" : null;

		Configure::load("SiteDesigns");
		$themes = array_keys(Configure::read("SiteDesigns.themes"));
		$this->out("THEMES AVAILABLE=".join(", ", $themes));

		$randtheme = $themes[rand(0,count($themes)-1)];

		$slogan = rand(0,1) ? "Slogan slogan slogan..." : null;

		$design = array(
			'title'=>$title,
			'subtitle'=>$slogan,
			'site_logo_id'=>$logo_id,
			'right_text'=>$header_text_right,
			'facebook_url'=>$facebook,
			'twitter_url'=>$twitter,
			'theme'=>$randtheme,
			#'footer_message'=>'Example footer message. '.$this->Example->lorem(20)
		);

		$this->save('SiteDesign', $design);
	}

	function _homepage($hostname, $title = 'Welcome to Example Site')
	{
		$this->set_hostname($hostname);
		if($this->reset("Homepage", null, true)) { return; } # Reset only.

		$homepage = $this->Example->randomize("Homepage", $title);

		$this->save('Homepage',$homepage);
	}

	function _about($hostname)
	{
		$this->set_hostname($hostname);
		if($this->reset("AboutPage", null, true)) { return; } # Reset only.

		$about = $this->Example->randomize("AboutPage");

		$this->save('AboutPage', $about);
	}

	function _contact($hostname)
	{
		$this->set_hostname($hostname);
		if($this->reset("ContactPage", null, true)) { return; } # Reset only.

		$contact = $this->Example->randomize("ContactPage");

		$this->save('ContactPage', $contact);

		# Add random contacts.
		$total_contacts = rand(3, 15);
		for($i = 0; $i < $total_contacts; $i++)
		{
			$this->_contact_item($hostname);
		}
	}

	function _contact_item($hostname) # contact list item
	{
		if($this->reset("Contact")) { return; } # Reset only.

		$item = $this->Example->randomize("Contact"); 

		$this->save('Contact', $item);
	}

	########################################################
	function _topics($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		if($this->reset("Page", array('parent_id'=>null))) { return; } # Reset only.

		# Never have more than 4 topics.
		$existing = $this->Page->count(array('Page.parent_id'=>null));

		$max = 4;
		$total = !empty($total) ? $total : rand(2,$max);
		$total -= $existing; # If too many, no more will be created; unless reset.

		if($total < 0)
		{
			$this->out("Already have $max topics, skipping...");
		}

		for($i = 0; $i < $total; $i++)
		{
			$topic = $this->Example->randomize("Topic");
			$this->save('Page', $topic);
		}

		# Add sub-pages to existing topics. Can be a lot.
		# between 5 and 15 pages per topic TOTAL
		$topic_ids = $this->Page->fields('id', array('Page.parent_id'=>null));

		foreach($topic_ids as $tid)
		{
			$this->_subpages($hostname, $tid);
		}

	}

	function _subpages($hostname, $pid, $total = null) # Topic, project OR page
	{
		$this->Page->id = $pid;

		if($this->reset("Page", array('Page.parent_id'=>$pid))) { return; } # Reset only.

		$this->set_hostname($hostname);
		$sub_existing = $this->Page->count(array('Page.parent_id'=>$pid));
		$this->hr();
		# double check if type is accurate

		$max_sub = 15;
		$total = !empty($total) ? $total : rand(0, $max_sub); # Allowed
		$this->out("PAGE_ID=$pid HAS $sub_existing, MAX=$total");

		if($total < $sub_existing) # Too much
		{
			$this->out("Already have UP TO $total sub-pages. Skipping...");

		}

		for($j = 0; $j < $total-$sub_existing; $j++)
		{
			# Add sub-page to topic.
			$subpage = $this->Example->randomize('Subpage',$pid);
			$this->save("Page", $subpage);
		}
	}

	########################################################
	function _projects($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		if($this->reset("Project")) { return; } # Reset only.

		# Never have more than 15 projects.
		$existing = $this->Project->count();

		$this->hr();

		$max = 15;
		$total = !empty($total) ? $total : rand(3,$max);

		if($total < $existing)
		{
			$this->out("Too many projects already, not adding...");
		}

		for($i = 0; $i < $total-$existing; $i++)
		{
			$project = $this->Example->randomize("Project");
			$this->save('Project', $project);
		}

		# Add sub-pages to existing projects. Can be a lot.
		# between 5 and 15 pages per project TOTAL
		$project_ids = $this->Project->fields('id');
		foreach($project_ids as $pid)
		{
			Configure::write("project_id", $pid);
			$this->_topics($hostname);

			$this->_news($hostname);
			$this->_events($hostname);
			$this->_photo_albums($hostname);
			$this->_links($hostname);
			$this->_downloads($hostname);
		}
		Configure::write("project_id",null);
	}

	function _member_page($hostname)
	{
		$this->set_hostname($hostname);
		if($this->reset("MemberPage")) { return; } # Reset only.

		$this->hr();

		$memberPage = $this->Example->randomize("MemberPage");
		$this->save('MemberPage', $memberPage);

		# Add sub-content to member page. Can be a lot.
		# between 5 and 15 pages per project TOTAL
		Configure::write("members_only", true);
		$this->_subpages($hostname);

		$this->_news($hostname);
		$this->_events($hostname);
		$this->_photo_albums($hostname);
		$this->_links($hostname);
		$this->_downloads($hostname);
		Configure::write("members_only",null);
	}

	function _pages($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		if($this->reset("Page", array('Page.parent_id'=>0))) { return; } # Reset only.

		# Never have more than 15 pages.
		$existing = $this->Page->count(array('Page.parent_id'=>0));
		$max = 15;

		$total = !empty($total) ? $total : rand(1,$max);
		$total -= $existing; # If too many, no more will be created; unless reset.

		for($i = 0; $i < $total; $i++)
		{
			$page = $this->Example->randomize("Page");
			$this->save('Page', $page);
		}

		# Add sub-pages to existing pages. Can be a lot.
		# between 5 and 15 pages per page TOTAL
		$page_ids = $this->Page->fields('id', array('Page.parent_id'=>0));

		foreach($page_ids as $tid)
		{
			$this->_subpages($hostname, $tid);
		}
	}

	function _news($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		if($this->reset("NewsPost")) { return; } # Reset only.

		$total = !empty($total) ? $total : rand(2,20);

		for($i = 0; $i < $total; $i++)
		{
			$newsPost = $this->Example->randomize("NewsPost");
			$this->save('NewsPost', $newsPost);
		}
	}

	function _events($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		if($this->reset("Event")) { return; } # Reset only.

		# Ensure at least 5-10 locations, 5-10 contacts
		$totalContacts = rand(5,10);
		$totalLocations = rand(5,10);

		$locationCount = $this->EventContact->find('count');

		for($i = 0; $i < $totalLocations - $locationCount; $i++)
		{
			$this->_event_locations($hostname);
		}

		$contactCount = $this->EventContact->find('count');
		for($i = 0; $i < $totalContacts - $contactCount; $i++)
		{
			$this->_event_contacts($hostname);
		}
		


		if(empty($total)) { $total = rand(2,10); }

		for($i = 0; $i < $total; $i++)
		{
			$event = $this->Example->randomize("Event");
			$this->save('Event', $event);
		}
	}

	function _event_contacts($hostname)
	{
		$this->set_hostname($hostname);
		if($this->reset("EventContact")) { return; } # Reset only.

		$contact = $this->Example->randomize("EventContact");
		$this->save("EventContact", $contact);
	}

	function _event_locations($hostname)
	{
		$this->set_hostname($hostname);
		if($this->reset("EventLocation")) { return; } # Reset only.

		$location = $this->Example->randomize("EventLocation");

		$this->save("EventLocation", $location);
	}

	function _photo_albums($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		if($this->reset("PhotoAlbum")) { return; } # Reset only.

		$total = !empty($total) ? $total : rand(2, 9);

		for($i = 0; $i < $total; $i++)
		{
			$album = array(
				'title'=>"Photo Album ".rand(10,200),
				'description'=>$this->Example->lorem(200),
			);

			$album_id = $this->save("PhotoAlbum", $album);

			$this->Example->randomize("Photos", $album_id);

		}
	}

	function _links($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		# Just one link page. Defaults are ok.
		if($this->reset("Link")) { return; }

		$this->Example->randomize("Links");

	}

	function _downloads($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		# Just one link page. Defaults are ok.
		if($this->reset("Download")) { return; }

		# Create categories.
		$maxcats = 9;

		# Create files, random category, or none.

		$total = !empty($total) ? $total : rand(4, 25);

		for($i = 0; $i < $total; $i++)
		{
			$file = $this->Example->random_upload("downloads");
			$file['description'] = $this->Example->lorem(rand(50,100));

			$this->save("Download", $file);
		}
	}

	
#	function _videos($hostname, $total = null)
#	{
#		$this->set_hostname($hostname);
#		# Just one link page. Defaults are ok.
#		if($this->reset("VideoPage")) { return; }
#
#		$videoPage = $this->VideoPage->find('first_default'); # auto-create
#		$page_id = $videoPage['VideoPage']['id'];
#		error_log("PID=$page_id");
#
#		/* NOT categorized
#
#		# Create categories.
#		$maxcats = 9;
#
#		$existingCats = $this->VideoCategory->find('count');
#
#		$totalcats = rand(3, $maxcats);
#
#		for($i = 0; $i < $totalcats - $existingCats; $i++)
#		{
#			$cat = array(
#				'title'=>'Category '.rand(1,25),
#				'video_page_id'=>$page_id,
#			);
#
#			$this->save("VideoCategory", $cat);
#		}
#
#		$catIDs = $this->VideoCategory->fields('id');
#		*/
#
#		$total = !empty($total) ? $total : rand(2, 9);
#
#		for($i = 0; $i < $total; $i++)
#		{
#			#$catid = rand(0,1) ? $catIDs[rand(0,count($catIDs)-1)] : null;
#			$video = $this->Example->randomize("Video");
#			$video_url = $video['video_url'];
#
#			$videoDetails = $this->VideoItem->details($video_url);
#			# Will get title, description, url, id, etc from video server.
#
#			$videoDetails['VideoItem']['video_page_id'] = $page_id;
#			$videoDetails['VideoItem']['created'] = $this->Example->random_date();
#			if(empty($videoDetails['VideoItem']['title']))
#			{
#				$videoDetails['VideoItem']['title'] = "Video ".rand(1000,50000);
#			}
#			if(empty($videoDetails['VideoItem']['description']))
#			{
#				$videoDetails['VideoItem']['description'] = $this->Example->lorem(rand(50,100));
#			}
#
#			$this->save("VideoItem", $videoDetails['VideoItem']);
#		}
#	}
#
#	function _audio($hostname, $total = null)
#	{
#		$this->set_hostname($hostname);
#		# Just one link page. Defaults are ok.
#		if($this->reset("AudioPage")) { return; }
#
#		$linkPage = $this->AudioPage->find('first_default'); # auto-create
#		$page_id = $linkPage['AudioPage']['id'];
#
#		/*
#		# Create categories.
#		$maxcats = 9;
#
#		$existingCats = $this->AudioCategory->find('count');
#
#		$totalcats = rand(3, $maxcats);
#
#		for($i = 0; $i < $totalcats - $existingCats; $i++)
#		{
#			$cat = array(
#				'title'=>'Category '.rand(1,25),
#				'audio_page_id'=>$page_id,
#			);
#
#			$this->save("AudioCategory", $cat);
#		}
#
#		#$catIDs = $this->AudioCategory->fields('id');
#		*/
#
#
#		# Create audios, random category, or none.
#
#		$total = !empty($total) ? $total : rand(4, 25);
#
#		for($i = 0; $i < $total; $i++)
#		{
#			#$catid = rand(0,1) ? $catIDs[rand(0,count($catIDs)-1)] : null;
#
#			$audio = $this->Example->random_upload("audio");
#			#$audio['audio_category_id'] = $catid;
#			$audio['audio_page_id'] = $page_id;
#			$audio['summary'] = $this->Example->lorem(rand(50,100));
#			$audio['description'] = $this->Example->lorem(rand(200,400));
#
#			$this->save("AudioItem", $audio);
#		}
#	}

	########################################3
	# Visits can be falsified from command line, no need for GUI


	function _visits($hostname, $total = null)
	{
		$this->set_hostname($hostname);
		if($this->reset("SiteVisit")) { return; }

		$total = !empty($total) ? $total : (rand(0,4) ? rand(5,15) : rand(16,100)); # Per day

		# Dates for dispersing...
		$from = !empty($this->params['from']) ? $this->params['from'] : "1 month ago";
		$to = !empty($this->params['to']) ? $this->params['to'] : "now";

		for($i = 0; strtotime("$from +$i days") < strtotime("$to"); $i++) 
		{ # Do something for EVERY day!

			for($j = 0; $j < $total; $j++) # Multiple
			{
				$this->hr();
				$start = $this->Example->random_date($from, $to);
				$end = $this->Example->random_date($start, "$start +3 hours");

				$random_url = Router::url($this->Example->random_internal_url());

				$ref_keywords = $this->Example->random_ref_keywords();
				$ref_domain = $this->Example->random_ref_domain();
				$ref_path = $this->Example->random_ref_path();

				$country = $this->Example->randcountry();
				$state = $country == 'United States' ? $this->Example->randstate() : null;
				$city = $this->Example->randcity();

				$visit = array(
					'start'=>$start,
					'end'=>$end,
					'ip'=>$this->Example->random_ip(),
					'session_id'=>$this->Example->random_session_id(),
					'browser'=>$this->Example->random_browser(),
					'url'=>$random_url,
					'fake'=>1, # So we can remove later if needed, w/o wiping legit stuff.
					'refkeywords'=>$ref_keywords,
					'refdomain'=>$ref_domain,
					'refpath'=>$ref_path,
					'city'=>$city,
					'state'=>$state,
					'country'=>$country,
					# other stuff isn't really used/checked.
				);

				$visit_id = $this->save("SiteVisit", $visit);

				# Now simulate page views for that visit.

				$this->_page_views($hostname, $visit_id);
			}
		}
	}

	function _page_views($hostname, $visit_id, $total = null)
	{
		# Stuff should be relatively within a few hours of each other...for a legit visit

		# Get fake info from visit, when start, end????
		$this->set_hostname($hostname);
		if($this->reset("SitePageView")) { return; }

		$total = !empty($total) ? $total : (rand(0,4) ? rand(1,3) : rand(4,9)); # Per visit

		# Get start and end in visit. May need to extend end, but might not.
		$this->SiteVisit->id = $visit_id;
		$visitStart = $this->SiteVisit->field('start');
		$visitEnd = $this->SiteVisit->field('end');
		$visit_ip = $this->SiteVisit->field('ip');
		$session_id = $this->SiteVisit->field('session_id');

		for($i = 0; $i < $total; $i++)
		{
			$created = $this->Example->random_date($visitStart, "$visitStart +2 hours");

			# SPLIT URL INTO PIECES, SO STATS CHART CAN BREAK APART and get name
			$random_url = $this->Example->random_internal_url();

			$random_title = Inflector::singularize(Inflector::humanize($random_url['controller'])) . " ".rand(1000,99999);
			if(in_array($random_url['controller'], $this->singletons))
			{
				$random_title = Inflector::singularize(Inflector::humanize($random_url['controller']));
			}

			$pageView = array(
				'ip'=>$this->Example->random_ip(),
				'site_visit_id'=>$visit_id,
				'url'=>Router::url($random_url),
				'controller'=>$random_url['controller'],
				'action'=>$random_url['action'],
				'page_id'=>array_pop($random_url),
				'created'=>$created,
				'title'=>$random_title,
				'fake'=>1, # So we can remove later if needed, w/o wiping legit stuff.
				# No need for refkeywords, refdomain, etc...
			);

			$this->save("SitePageView", $pageView);

			# MAYBE SHARE PAGE TOO!
			if(rand(0,10) > 2)
			{
				$social_sites = array('facebook','twitter','email');
				$social_site = $social_sites[rand(0,count($social_sites)-1)];

				$pageShare = array(
					'social_site'=>$social_site,
					'page_url'=>Router::url($random_url),
					'title'=>$random_title,

					'created'=>$created,
					#'fake'=>1, # So we can remove later if needed, w/o wiping legit stuff.
					# No need for refkeywords, refdomain, etc...
				);
	
				$this->save("SiteShare", $pageShare);

			}

			# Update visit with 'end'
			if(empty($visitEnd) || strtotime($visitEnd) < strtotime($created))
			{
				$this->SiteVisit->saveField("end", $created);
			}
		}
	}
	
	function set_hostname($hostname, $nonexistent_abort = true)
	{
		if(!$this->Example->set_hostname($hostname) && $nonexistent_abort)
		{
			error_log("******************** COULD NOT FIND SITE/SET HOST TO $hostname **************************** \n\n\n");
			exit(0);
		}
	}






}

