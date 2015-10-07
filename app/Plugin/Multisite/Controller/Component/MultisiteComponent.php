<?

# Set $this->multisite = true, in AppController, some controllers, or disable in some....

class MultisiteComponent extends Component
{
	public $components = array('Session');

	public $file = null; # Load sites from Config file
				# Stored as Sites: [ {hostname: foo, domain: foo.com, name: Foo}, {...}, ]
	public $model = 'Site'; # Load from db

	public $adminPrefix = 'admin'; # For people who can manage site, reinable, pay, etc.
	public $internalSites = array('www'); # 'www','blog', etc.
	public $default_domains = array(); # Set in app...

	public $restoreUrl = null; # Where to go to restore site.

	public $hostname = null; 
	public $domain = null; 

	public $userRestoreFlag = null; # Need to have this field set to re-instate site

	public $allowedControllers = array();

	public $email_domain = null; # Whether emailer should use site-specific domain.

	protected $_site = array(); # Site record.
	var $default_domain = null; # Current one of set.. auto-calculated

	function initialize(Controller $controller)
	{
		parent::initialize($controller);

		$this->controller = $controller;

		$domain = $controller->default_domain = $this->default_domain = $this->get_default_domain();
		Configure::write("default_domain", $domain);

		if(!$this->enabled($controller)) { #error_log("NO MULTI {$_SERVER['HTTP_HOST']} "); 
			$this->Session->delete("SiteDesign");
			$this->Session->delete("CurrentSite");
			return true; 
		} # Shouldnt be any sooner, else site barfs.

		$this->loadCrossDomainSession(); # MUST happen before any/all calls to Session!!!!

	}

	function startup(Controller $controller)
	{
		if(!$this->enabled($controller)) { return; }

		# Ensure site is valid. Also save specifics to memory, for filtering, etc.
		$this->validateSite();
		# Load site_id for record filtering.
		$this->loadSite();

		$this->checkSiteActive(); # Not disabled
	}

	function enabled($controller = null) # Whether enabled or not.
	{ # Can be set in controller itself.
		if(empty($controller)) { $controller = $this->controller; }

		# Don't enable for cake error pages, in case we cause.
		# Need to handle unfound site somehow.
		if(is_a($controller,"CakeErrorController")) { return false; }

		if(!Configure::read("multisite") && empty($this->controller->request->params['hostname'])) { return false; } # Never enabled in routes/bootstrap.

		if(!empty($controller->request->params['prefix']))
		{
			return true; # Always on admin, etc
		}
		return !empty($controller->multisite) ? $controller->multisite : false; # Always must be present.
	}


	function checkSiteActive()
	{
		if(!empty($this->controller->request->params['requested'])) { return; } # allow subrequests...

		$site = $this->site();
		$prefix = !empty($this->controller->request->params['prefix']) ? ($this->controller->request->params['prefix']) : null;
		$controller = !empty($this->controller->request->params['controller']) ? ($this->controller->request->params['controller']) : null;
		if(!empty($site['Site']['disabled']))
		{
			if($prefix == 'manager') { return; } # Continue as ususal
			if($prefix == $this->adminPrefix)
			{
				if(!empty($this->userRestoreFlag) && !$this->controller->Auth->user($this->userRestoreFlag) && !$this->controller->is_god())
				{
					return $this->controller->setError("Please have a website administrator from your organization sign in to re-enable your website","/");
				} else { # Authorized to restore.
					$allowedControllers = array_merge( array(Inflector::tableize($this->model), 'users'), $this->allowedControllers);
					# Can manage users and site itself.
					if(!in_array($controller, $allowedControllers))
					{
						$this->controller->redirect( !empty($this->restoreUrl) ?  $this->restoreUrl : "/");
					}
				}
			} else {
				$this->controller->render("/construction");
			}
		}
	}

	# Hacked to work with hp.com/rescues/foobar/*
	function host() { return !empty($this->request->params['hostname']) ? $this->request->params['hostname'] : HostInfo::host(); }

	function get_default_domain() # Based on HTTP_HOST
	{
		# Might be session var....
		$default_domains = HostInfo::default_domains();
		$default_domain = $default_domains[0]; # Default. Production domain if we're actually using a custom domain.
		foreach($default_domains as $domain)
		{
			if(preg_match("/$domain/", $this->host()))
			{
				$default_domain = $domain;
			}
		}
		return $default_domain;
	}

        function loadCrossDomainSession() # From corss-domain (www->site) redirect.
        {
		if(!empty($this->controller->request->params['requested'])) { return; } # Don't try to load again for sub-requests.

		# MUST HAPPEN BEFORE ANY CALLS TO SESSION READING/WRITING
		# OTHERWISE WE LOSE PREVIOUS STUFF (ie sess read from wrong place)

                $cookie = Configure::read("Session.cookie");
		#error_log("COOKIE=$cookie");
                if(!empty($_GET[$cookie]))
                {
			$id = $_GET[$cookie];
			#error_log("SESSION SETTING=$id");

			if($this->Session->started()) # TOO LATE!
			{
				error_log("WTF. CANT IMPORT CROSS DOMAIN SESSION BECAUSE SOMETHING STARTED A SESSION READ/WRITE BEFORE MULTISITE COMPONENT. SESSION WILL BE WIPED OUT AND LOST. FIX!!!!!!!!");
				throw new Exception("Session already started. Cannot import previous session.");

			}

			$this->Session->id($id); # Cake version doesnt seem to work.
			#session_start();
                }

        }

	function validateSite() # Check if URL points to a site, then save data into session if so.
	{
		list($hostname, $domain) = $this->getSiteHostDomain(); # Just extract parts from url.
		error_log("H=$hostname, D=$domain");
		$this->hostname = $hostname;

		# If domain doesnt match expected one, then assume it's custom for the site.
		if(!$this->controller->multisite)
		{
			$this->Session->delete("CurrentSite");
			return;
		}

		if(!empty($hostname))
		{
			if($hostname == $this->Session->read("CurrentSite.Site.hostname"))
			{
				# Get modified timestamp of site
				$session_modified = $this->Session->read("CurrentSite.Site.modified");
				$site_modified = $this->read($hostname, 'modified');

				if(strtotime($site_modified) == strtotime($session_modified))
				{
					return;
				} # Else, timestamps don't match, something changed. refresh.
			}

			# Else, not in session yet, so lookup
			$current_site = $this->read($hostname);
			$this->set_current_site($current_site);

			if(empty($current_site) && !in_array($hostname, $this->internalSites)) # Setting multisite config var when applicable should make internalSites not necessary.
			{ # Invalid site not in system.
				return $this->notFound($hostname);
			}
		} else if (!empty($domain)) {
			if($domain == $this->Session->read("CurrentSite.Site.domain"))
			{
				$session_modified = $this->Session->read("CurrentSite.Site.modified");
				$site_modified = $this->read($hostname, 'modified');

				if(strtotime($site_modified) == strtotime($session_modified))
				{
					return;
				}
			} 
			# Not in session yet.
			$current_site = $this->read($domain);
			$this->set_current_site($current_site);

			$this->hostname = $this->Session->read("CurrentSite.Site.hostname"); # Not known when we look up by domain

			if(empty($current_site) && !in_array($hostname, $this->internalSites)) # Setting multisite config var when applicable should make internalSites not necessary.
			{ # Invalid site not in system.
				return $this->notFound($domain);
			}
		}

		Configure::write("hostname",$this->hostname);

	}

	function read($hostname, $key = null)
	{
		$domain = null;
		$id = null;
		if(is_numeric($hostname)) { $id = $hostname; $hostname = null; }

		if(strpos($hostname,'.') !== false) {
			$domain = $hostname;
			$hostname = null;
		}

		# Read from backend...
		if($this->file)
		{
			Configure::load($this->file);
			$sites = Configure::read('Sites');

			$siteArray = $domain ?
				Hash::extract($sites, "{n}[domain=$domain]") :
				Hash::extract($sites, "{n}[hostname=$hostname]");
			$site = !empty($siteArray) ? $siteArray[0] : null;


			if(empty($site)) { return null; } # NOT FOUND
			$site['modified'] = filemtime(APP."/Config/{$this->file}.php");

			# Put in fake "Site" key
			if(!empty($key))
			{
				return !empty($site[$key]) ? $site[$key] : null;
			} else {
				return array('Site'=>$site); # Compatible with db.
			}
		} else if($this->model) { # DB
			$this->controller->loadModel($this->model);
			$this->{$this->model} = $this->controller->{$this->model};
			$this->{$this->model}->recursive = 1;
			$cond = $id ? array("{$this->model}.id"=>$id) :
				(!$domain || !$this->{$this->model}->hasField("domain") ?
					array("{$this->model}.hostname"=>$hostname) :
					array("{$this->model}.domain"=>$domain)
				);

			if(!empty($key)) {
				return $this->{$this->model}->field($key, $cond);
			} else {
				return $this->{$this->model}->first($cond);
			}
		}
		return null;
	}

	function set_current_site($site)
	{
		if(empty($site)) { return null; } # None/invalid.

		$ignore_controllers = array('tracker');

		if(!empty($this->controller->params['controller']) && in_array($this->controller->params['controller'], $ignore_controllers)) { return; }

		if(is_string($site)) { # Need to look up.
			$site = $this->read($site);
			$this->Session->write("CurrentSite", $site);
		} else if(!empty($site['Site'])) {
			$this->Session->write("CurrentSite", $site);
		}

		# Store hostname for easy reference.
		$this->hostname = $site['Site']['hostname']; # Save, esp for 'www' checks

		return $this->get_current_site();
	}

	function loadSite()
	{
		$site = $this->get_current_site();
		if(empty($site)) { 
			#$this->Session->delete("CurrentSite");
			return;
		}
		if(!empty($this->model) && !empty($site['Site']['id'])) # Multiple sites in same database.
		{
			Configure::write("site_id", $site['Site']['id']);
			$this->controller->Site->id = $site['Site']['id']; # Save for convenience.
		}
		# Theme and layout files hardcoded for site, ie rescue.
		if(!empty($site['Site']['theme']))
		{
			Configure::write("theme", $site['Site']['theme']);
		}
		if(!empty($site['Site']['layout']))
		{
			Configure::write("layout", $site['Site']['layout']);
		}

		Configure::write("site_title", $site['Site']['title']);
		Configure::write("CurrentSite", $site);
		# ALSO save email_domain
		if(!empty($this->email_domain))
		{
			Configure::write("email_domain", $this->getSiteHostDomain());
			# Based on URL
		}
	}

	function notFound($hostname = '')
	{
		$address = preg_match("/[.]/", $hostname) ? $hostname : "$hostname.{$this->default_domain}";
		throw new SiteNotFoundException("Website '$address' does not exist");
	}


	function beforeRender(Controller $controller)
	{
		$controller->set("current_site", $site=$this->site());
	}

	function getHostId($host = null)
	{
		list($hostname,$domain) = $this->getSiteHostDomain($host);
		$cond = array();
		if(!empty($hostname)) { $cond['hostname'] = $hostname; }
		else { $cond['domain']  = $domain; }
		return $site_id = $this->controller->Site->field("id", $cond);
	}

	function getSiteHostDomain($host = null) # Get accurate site info, possibly existing if in session and haven't changed sites.
	{
		$hostname = $domain = null;

		if(empty($host)) { $host = $this->host(); }

		$server_host = preg_replace("/^www[.]/", "", $host); # So they don't accidentally put in

		if($server_host == $this->default_domain) { $server_host = "www.{$this->default_domain}"; } # So we find 'www'., mainly for hopefulpress.com => www.hopefulpress.com

		if(preg_match("/(.*)[.]{$this->default_domain}/", $server_host, $matches)) # A subdomain, ecovineland.hopefulpress.com
		{
			$hostname = $matches[1];
		} else { #  Something else, probably a full domain, www.ecovineland.com
			$domain = preg_replace("/^www[.]/", "", $server_host); # Get rid of 'www', if there.
		}

		if(!empty($this->controller->request->params['hostname'])) # Override.
		{
			$hostname = $this->controller->request->params['hostname'];
		}
		return array($hostname,$domain); # If subdomain of hp.com, domain here will be null, otherwise, hostname will be null and domain will be  what we need.
	}

	function get_site_id()
	{
		return $this->get_site('id');
	}

	function get_site($key = null)
	{
		return $this->get_current_site($key);
	}

	function get($key = null)
	{
		return $this->get_current_site($key);
	}

	function site($key = null)
	{
		return $this->get_current_site($key);
	}

	function hostname()
	{
		return $this->get_current_site('hostname');
	}

	function saveField($k,$v)
	{
		$this->controller->Site->id = $this->get_site_id();
		return $this->controller->Site->saveField($k,$v);
	}

	function get_current_site($key = null)
	{
		return !empty($key) ? 
			$this->Session->read("CurrentSite.Site.$key") :
			$this->Session->read("CurrentSite");
	}

	function url($path = '/')
	{
		$domain = $this->get_current_site("domain");
		$hostname = $this->get_current_site("hostname");
		$url = "http://";
		if(!empty($domain)) { $url .= $domain; }
		else { $url .= "$hostname.".$this->get_default_domain(); }
		return $url.$path;
	}

	function redirect($path, $site = null) # Goes to url on site, esp from marketing site.
	{
		#echo "SITE=".print_r($site,true);
		if(!substr($path, 0, 1) === '/') { $path = "/$path"; }

		if(empty($site)) { $site = $this->get("hostname"); } # Retreive from session if need be.

		$cookname = Configure::read("Session.cookie");
		$this->controller->redirect("http://$site.{$this->default_domain}$path".(!empty($_COOKIE[$cookname]) ? ("?$cookname=".session_id()) : ""));
	}

}

class SiteNotFoundException extends NotFoundException {

}
