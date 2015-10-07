<?php

App::uses('AppCoreController', 'Core.Controller');

class AppController extends AppCoreController {
	var $components = array(
		'RequestHandler',
		'Core.MethodDefaults'=>array(
			'prefixMaps'=>array(
				'manager'=>array('admin','user',''),
				'members'=>array('user',''),
				'owner'=>array('admin','user',''),
				'rescuer'=>array('admin','user',''),
				'admin'=>array('user',''),
				'user'=>'',
				'member'=>''
			),
		),
		#'Members.Members', # Since modifies Auth via deny()
		'Auth'=>array(
			'className'=>'UserCore.UserAuth',
			'authError'=>false,
			'managerField'=>'manager', # Users with 'manager' flag set can log into any site.
			'loginAction'=>array('plugin'=>false,'controller'=>'users','action'=>'login'), # keeps prefix, ie custom sign in page w/details #'/user/users/login',
			'loginRedirect'=>'/',#user/dashboard',
			'logoutRedirect'=>'/',
			'publicAllowed'=>true,
			'accountSaveRedirect'=>true, # after own account is saved; true = referer
			'prefixes'=>array( # Map prefixes to access levels
				'user'=>true, # /user/* for logged in users
				'admin'=>'admin', # /admin/* for admin flag users
				'owner'=>false, # /owner/* for site owner only
				'rescuer'=>'rescuer',
				'volunteer'=>'volunteer',
				'foster'=>'foster',
				'adopter'=>'adopter',
			)
		),
		/*
		'Multisite.Multisite'=>array(
			'userRestoreFlag'=>'admin', # Only users with this flag can restore site.
			'allowedControllers'=>array('stripe_billing'), # plus users/sites
			'restoreUrl'=>"/admin/billing",
		), # Auto-loads CurrentSite and site_id if enabled at Config level
		*/
		'Sortable.Sortable',
		'Session',
		'Core.CoreEmail'=>array(
			'domain'=>'hopefulpress.com', # May vary! XXX TODO
			'replyTo'=>'notification', # XXX TODO 
			'devEmail'=>'tomas@malysoft.com',
			'support'=>'support@hopefulpress.com'
		),
		'Project.Projectable',
		#'Tracker.Tracker',
		'Editable.Editable',
	);

	var $helpers = array(
		'Html'=>array('className'=>'HpHtml'),
		'Form'=>array('className'=>'HpForm'),
		'Time'=>array('className'=>'HpTime'), # Not sure but doesnt like Html references
		'Slug'=>array('className'=>'Sluggable.Slug'),
		'Site'=>array('className'=>'Multisite.Site'),
		'Share'=>array('className'=>'Sharable.Share'),
		'Less'=>array('className'=>'CakeLess.Less'),
		'Facebook.Facebook',
	);
	var $uses = array(
		'User',
		'Rescue',
		'Adoptable',
		/*
		'Page',
		'AboutPage',
		'ContactPage',
		'LinkPage',
		'Link',
		'NewsPost','Event','PhotoAlbum',
		'Resource',
		'DownloadPage',
		'Download',
		'Project.Project',
		'Members.MemberPage',
		'SiteDesign',
		'Rescue.Adoptable',
		'Rescue.AdoptionOverview',
		'Rescue.AdoptionForm',
		'Rescue.AdoptionStory',
		'Rescue.EducationPage',
		'Rescue.FosterOverview',
		'Rescue.VolunteerOverview',
		'Rescue.FosterForm',
		'Rescue.VolunteerForm',
		"Stripe.StripeCredential",
		"Paypal.PaypalCredential",
		"Newsletter.MailchimpCredential",
		*/
	);

	var $layout = null;#'Core.default';
	# If nothing explicitly set by end of action, set default.

	var $admin_controllers = array('users'); # Don't bother with theme on these entire controllers.

	var $disabled_controllers = array('users','stripe_billing'); # Valid when site  disabled.

	var $multisite = true; # Enable (special controllers can disable filtering)

	var $denied = array('edit','delete','add');

	var $rescue = null; # Current rescue?
	var $rescue_id = null; # Current rescue?

	function beforeFilter()
	{
		Configure::write("in_admin", $this->me()); # Is user logged in? (do we show editing controls) XXX TODO

		Configure::write("hostname", $this->Session->read("CurrentSite.Site.hostname")); # Might be  used in helpers,etc.
		
		if(!empty($this->request->params['manager'])) # Don't auto-assign users when not explicit. I'm not part of user sites.
		{
			Configure::write("User.autouser", false);
		}

		# Load possible rescue details
		$this->loadRescue();

		# XXX TODO Auth should implement site_id scope if not manager prefix
		return parent::beforeFilter();
	}

	function get_site_id()
	{
		return $this->Multisite->get_site_id();
	}

	function site_id()
	{
		return $this->get_site_id();
	}

	function site($var = null)
	{
		if(empty($this->Multisite)) { return null; }
		return $this->Multisite->site($var);
	}
	function user($key)
	{
		#return $this->Auth->user("User.$key");
		return $this->Auth->user($key); # User is stored as Session.Auth, NOT Session.Auth.User
	}

	function beforeRender()
	{
		# DEFAULT
		if(!empty($this->request->params['manager']))
		{
			Configure::write("layout", "Www.plain");
		}
		# Controller action has precedence on setting $this->layout


		# Better layout...
		if($this->RequestHandler->isRss())
		{
			$this->layout = 'Core.default';
		}
		/* DISABLE FOR NOW
		else if(empty($this->layout) && !$this->request->is('ajax') && empty($this->request->params['requested']) && 
			!empty($this->request->params['prefix']) #&& 
			|| in_array($this->request->params['controller'], $this->admin_controllers)
			# ALL ADMIN PAGES WITHOUT THEME? edit, view and index
		)
		{
			$this->layout = 'admin'; # Admin-style edit, without theme.
		}
		*/
		
		if(empty($this->layout) && !empty($this->request->query['preview'])) # Allows sub-requests for main content when previewing changes to design.
		{
			$this->set("preview", $this->request->query['preview']);
			$this->layout = 'preview';
			# Better for layout to strip out unwanted pieces....
		}

		parent::beforeRender();

		#
		$this->loadRescueContent();


		# Load content for nav
		if(!empty($this->Multisite) && $this->Multisite->enabled()) # Might be www/blog
		{
			$this->loadNav();
			$this->loadSiteDesign();
			$this->loadAdminNotices(); # AFTER design since we may want to mention changing it.
		}

		# Hopefully this is a better place to set Rescue.rescue
		if(empty($this->layout) && ($layout = Configure::read("layout")) && !$this->request->is('ajax') && empty($this->RequestHandler->ext)) # Set in routing, ie so errors in www don't go to 'default'
		{
			$this->layout = $layout;
		}

		if(empty($this->layout))
		{
			$this->layout = 'Core.default'; # Default.
		}

		$this->set("default_domain", HostInfo::default_domain()); # Always available.

		$this->set("hostname", Configure::read("hostname"));

	}

	function loadAdminNotices()
	{
		if(!empty($this->request->params['requested'])) { return; } # Irrelevant.

		$notices = array();

		if($this->Auth->user("anon") && !$this->site("user_id")) # Owner and no account yet
		{
			$notices[] = 'signup';
		}
		if($this->Auth->user() && (!$this->site("plan") || $this->site("trial")))
		{
			$notices[] = 'trial';
		}

		$design = $this->_viewVars['siteDesign'];

		if(empty($design['modified']) || $design['created'] == $design['modified']) # Never changed any part of design
		{
			$notices[] = 'design';
		}
		$this->set("adminNotices", $notices);
	}

	function loadSiteDesign()
	{
		$siteDesign = $this->SiteDesign->singleton();

		$fields = array('theme','color1','color2');
		foreach($fields as $field)
		{
			if(isset($this->request->query[$field])) # Empty is ok. (set default)
			{
				$siteDesign['SiteDesign'][$field] = $this->request->query[$field];
			}
		}

		if(!empty($this->request->data['SiteDesign'])) # Preview....
		{
			foreach($this->request->data['SiteDesign'] as $k=>$v) { $siteDesign['SiteDesign'][$k] = $v; }
		}
		$this->set("siteDesign", $siteDesign);

		$this->theme = $siteDesign['SiteDesign']['theme']; # Allow for custom HTML templates

		if($theme = Configure::read("theme")) { $this->theme = $theme; }
		# Rescue gets promoted here....

		Configure::load("SiteDesigns");

		$this->set("themeSettings", $themeSettings = Configure::read("SiteDesigns.settings.{$this->theme}"));
	}

	function loadNav()
	{
		if(!Configure::read("site_id")) { return; } # Not in site

		#if($pid = Configure::read("project_id")) { return $this->loadProjectNav(); }

		$education_pages = $this->EducationPage->idurllist();

		$topics = $this->Page->idurllist(array('Page.parent_id'=>null));
		$topic_ids = $this->Page->fields("id", array('Page.parent_id'=>null));
		$subtopics = array();
		foreach($topic_ids as $tid)
		{
			$tidurl = $this->Page->field("idurl", array('Page.id'=>$tid));
			$subtopics[$tidurl] = $this->Page->idurllist(array('Page.parent_id'=>$tid));
		}

		$donationsEnabled = $this->StripeCredential->count() + $this->PaypalCredential->count();

		# Load mailchimp config.
		Configure::load("Newsletter.mailchimp");
		$mailchimpCredential = $this->MailchimpCredential->first();
		$mailingListEnabled = count($mailchimpCredential);
		# Enable mailchimp api...
		if(!empty($mailchimpCredential))
		{
			Configure::write("Mailchimp.apiKey", 
				$mailchimpCredential['MailchimpCredential']['access_token']."-".
				$mailchimpCredential['MailchimpCredential']['dc']);
		}

		$adoptableCount = $this->Adoptable->count(array('status'=>'Available'));
		$adoptionStoryCount = $this->AdoptionStory->count();
		$adoptionEnabled = $adoptableCount + $adoptionStoryCount + $this->AdoptionOverview->count();

		$nav = array(
			"newsCount"=>$this->NewsPost->count(),
			"eventsCount"=>$this->Event->count(),
			"photoCount"=>$this->PhotoAlbum->count(),
			"mailingListEnabled"=>$mailingListEnabled,
			"donationsEnabled"=>$donationsEnabled,
			"adoptionStoryCount"=>$adoptionStoryCount,
			"adoptableCount"=>$adoptableCount,
			"adoptionEnabled"=>$adoptionEnabled,
			"adoptionFormEnabled"=>$this->AdoptionForm->count(),
			"resourceCount"=>$this->Resource->count(),
			#"linkPage"=>$this->LinkPage->field("title"),
			"downloadCount"=>$this->Download->count(),
			"downloadPage"=>$this->DownloadPage->field("title"),
			"aboutPage"=>$this->AboutPage->field("title"),
			"contactPage"=>$this->ContactPage->field("title"),
			"topics"=>$topics,
			"education_pages"=>$education_pages,

			"volunteerEnabled"=>$this->VolunteerOverview->count(array('disabled'=>0)),
			"volunteerFormEnabled"=>$this->VolunteerForm->count(array('disabled'=>0)),
			"fosterEnabled"=>$this->FosterOverview->count(array('disabled'=>0)),
			"fosterFormEnabled"=>$this->FosterForm->count(array('disabled'=>0)),

			"projects"=>$this->Project->idurllist(),
			"subtopics"=>$subtopics,
			"other_pages"=>$this->Page->idurllist(array('parent_id'=>0)),
			"pageidurls"=>$this->Page->find('list',array('fields'=>array('id','url'))) # Provide mapping if needed
		);

		$this->set("nav", $nav);
	}

	function sort($catid = null) {  # Any models with 'ix' field
		$this->Sortable->sort($catid); 
	}

	# Don't exit out of projects unless explicit or not applicable
	function redirect($url = null, $status = null, $exit = true)
	{
		Configure::load("projectable");
		$projectable_controllers = Configure::read("Projectable.controllers");

		$parsedUrl = Router::parse(Router::url($url));
		$controller = !empty($parsedUrl['controller']) ? $parsedUrl['controller'] : null;

		if(in_array($controller, $projectable_controllers) && ($pid = Configure::read("project_id")) && !isset($url['project_id'])) # Bypass project if pass id/false
		{
			$url['project_id'] = $pid;
		}
		#error_log("REDIRECT@=".print_r($this->request->params,true)."=".print_r($url,true));
		return parent::redirect($url, $status, $exit);
	}

	function pid()
	{
		if(!empty($this->request->params['project_id']))
		{
			return $this->request->params['project_id'];
		}

		if(!empty($this->request->named['project_id']))
		{
			return $this->request->named['project_id'];
		}
		return Configure::read("project_id");
	}

	##############################################
	# Default redirects for record management....
	function user_index() { $this->redirect(array('user'=>null,'action'=>'index','project_id'=>Configure::read("project_id"))); }
	function user_view($id=null) { $this->redirect(array('user'=>null,'action'=>'view',$id,'project_id'=>Configure::read("project_id"))); }

	function admin_index() { $this->redirect(array('admin'=>null,'action'=>'index','project_id'=>Configure::read("project_id"))); }
	function admin_view($id=null) { $this->redirect(array('admin'=>null,'action'=>'view',$id,'project_id'=>Configure::read("project_id"))); }

	##############################################

	function is_god() { return $this->user("manager"); }
	function is_site_admin() { 
		#echo "AUTH=".print_r($this->Auth->user(),true);
		$manager = $this->user("manager");
		$admin = $this->user("admin");
		$me = $this->me();
		$site_owner = $this->site("user_id");
		#echo "MAN=$manager, A=$admin, ME=$me, SOWN=$site_owner";

		return ($manager || $admin || ($me && ($me == $site_owner)));
	}

	# 
	function editable($field, $id = null) # pk, name, value passed
	{
		return $this->Editable->editable($field, $id);
	}
	function edit_field($field, $id = null) # Load edit form
	{
		return $this->Editable->edit_field($field, $id);
	}
	
	# Must be here since controllers might not inherit from RescueApp (ie Users instead)
	function sendAdoptionEmail($adoption) # Form record.
	{
		$vars = array(
			'adoption'=>$adoption,
		);
		# For now, send to site owner (someday add setting)
		$adoptionAdmins = $this->User->fields('id',array('admin'=>1)); # All admins.
		$adoptionAdmins[] = $this->Multisite->get("user_id"); # Site owner.

		$from = !empty($adoption['Adoption']['email']) ?
			$adoption['Adoption']['email'] : null;
		$vars['from'] = $from; # Reply will go to the requestor...

		return $this->userEmail($adoptionAdmins, "Adoption application", "Rescue.adoption", $vars);
	}

	function sendVolunteerEmail($volunteer)
	{
		# FOR NOW.
		$volunteerAdmins = $this->User->fields('id',array('admin'=>1)); # All admins.
		$volunteerAdmins[] = $this->Multisite->get("user_id"); # Site owner too.
		return $this->userEmail($volunteerAdmins, "Volunteer application", "Rescue.volunteer", array('volunteer'=>$volunteer));
	}

	function sendFosterEmail($foster)
	{ # FOR NOW...
		$fosterAdmins = $this->User->fields('id',array('admin'=>1)); # All admins.
		$fosterAdmins[] = $this->Multisite->get("user_id"); # Site owner too.
		return $this->userEmail($fosterAdmins, "Foster application", "Rescue.foster", array('foster'=>$foster));
	}

	function sendDonationEmail($donation)
	{ # FOR NOW...
		$donateAdmins = $this->User->fields('id',array('admin'=>1)); # All admins.
		$donateAdmins[] = $this->Multisite->get("user_id"); # Site owner too.
		return $this->userEmail($donateAdmins, "Donation Received", "Donation.donation", array('donation'=>$donation));
	}

	function phpinfo()
	{
		echo phpinfo();
		exit(0);
	}

	function require_https()
	{
		if(empty($_SERVER['HTTPS']))
		{
			error_log("SERVER=".print_r($_SERVER,true));
			list($hostname,$default_domain) = HostInfo::hostparts();
			$path = Router::url();
			$this->redirect("https://$default_domain/~$hostname$path?HOPEFULPRESS=".session_id()); # Removes other query string....
		} else {
		}
	}

	function sendAdminEmail($subject,$template,$vars=array())
	{
		$site_owner_id = $this->site("user_id");
		$admins = $this->User->fields('email',array('OR'=>array('admin'=>1,'id'=>$site_owner_id)));
		return $this->userEmail($admins,$subject,$template,$vars);
	}

	function sendSiteOwnerEmail($subject,$template,$vars=array())
	{
		$site_owner_id = $this->site("user_id");
		return $this->userEmail($site_owner_id,$subject,$template,$vars);
	}

	function track($thing=null)
	{
		if(!empty($this->Tracker)) { return $this->Tracker->track($thing); }
		return false;
	}

	# Needs to be in beforeFilter() so we have rescue_id filtering for db.
	function loadRescue()
	{
		# For full sites, might be hostname, domain, etc...

		$this->set("rescuename",null); # So var always exists.

		if(!empty($this->request->params['rescue']))
		{
			$rescue = $this->Rescue->findByHostname($this->request->params['rescue']);
			if(empty($rescue))
			{
				$this->setError("Rescue not found",array('controller'=>'rescues','action'=>'index'));
			} else {
				$this->set("rescue", $rescue);
				$this->set("hostname", $this->request->params['rescue']);
				$this->set("rescuename", $this->request->params['rescue']);
				$this->set("rescue_id", $rescue['Rescue']['id']);
				Configure::write("rescue_id", $this->rescue_id = $rescue['Rescue']['id']); # For later record filtering.
				Configure::write("rescuename", $this->rescue = $rescue['Rescue']['hostname']); 
				# Save to $this->rescue and $this->rescue_id
				Configure::write("rescue", $rescue); 
			}
		}
	}

	# Rendering aspect.
	function loadRescueContent()
	{
		if($rescue = Configure::read("rescue"))
		{
			$rescue_id = $rescue['Rescue']['id'];
			# Nav etc???
			$nav = array();

			if(!empty(array_filter(array_intersect_key($rescue['Rescue'], array_flip(array('phone','email','city'))))))
			{
				$nav['contactPage'] = true;
			}

			if(!empty(array_filter(array_intersect_key($rescue['Rescue'], array_flip(array('history','restrictions'))))))
			{
				$nav['aboutPage'] = true;
			}

			$nav['adoptableCount'] = $this->Adoptable->count(array('rescue_id'=>$rescue_id,"status != 'Adopted'"));
			if($nav['adoptableCount'])
			{
				$nav['adoptionEnabled']=true;
			}

			$this->set("nav", $nav);
		}
	}

}

