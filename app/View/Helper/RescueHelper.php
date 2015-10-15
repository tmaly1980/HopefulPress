<?
class RescueHelper extends AppHelper
{
	var $rescuer_controllers = array(
		'rescues',
		'setup',
	);
	var $admin_controllers = array(
		'homepages',
		'about_pages',
		'about_page_bios',
		'contact_pages',
		'contacts',
		'volunteer_page_indices',
		'foster_page_indices',
		'adoption_page_indices',
		'foster_page_forms',
		'adoption_page_forms',
		'volunteer_page_forms',
	);

	var $dedicated_plans = array('dedicated','unlimited');

	function get($key=null)
	{
		$rescue = $this->_View->get("rescue");
		if(empty($rescue)) { return null; }
		if(empty($key))
		{
			return $rescue['Rescue'];
		} else {
			list($model,$key) = pluginSplit($key);
			if(empty($model)) { $model = 'Rescue'; }
			return !empty($rescue[$model][$key])  ? $rescue[$model][$key] : null;
		}
	}

	function dedicated($rescue = null)
	{
		if(!empty($rescue))
		{
			$plan = !empty($rescue['Rescue']) ? $rescue["Rescue"]['plan'] : $rescue['plan'];
		} else {
			$plan = $this->get("plan");
		}
		return in_array($plan,$this->dedicated_plans);
	}

	function hostname()
	{
		return $this->get("hostname");
	}

	function id() { return $this->get("id"); }

	function member() # Is current user a member  of the current rescue (has admin or volunteer access)
	{
		if($this->user("manager")) { return true; }
		return $this->volunteer(); # Or admin or owner.
	}

	function rescuer() { return $this->owner(); }

	function  owner()
	{
		if(!$this->id()) { return false; }
		$me = $this->me();
		if(empty($me)) { return false; }
		if($this->manager()) { return true; }
		return $this->get("user_id") == $me;
	}

	# ACL's stored in UserRescue, admin=>1 allows admin access.

	function admin($strict=false) # More than volunteer.
	{
		if(!$strict && $this->owner()) { return true; }
		$rid = $this->id();
		if(empty($rid)) { return false; }
		$user = $this->user(); #
		$path = "/RescueVolunteer[rescue_id=$rid][admin=1]/rescue_id";
		return !empty(Set::extract($path,$user));
	}

	function volunteer($strict=false) # true limits to just volunteers, not admins/owners.
	{
		if(!$strict && ($this->admin() || $this->foster())) { return true; } # Foster is a type of (non-admin) volunteer access.
		$rid = $this->id();
		if(empty($rid)) { return false; }
		$user = $this->user(); #
		$path = "/RescueVolunteer[rescue_id=$rid][admin=0]/rescue_id";
		return !empty(Set::extract($path,$user));
	}

	function foster($strict=false) # true limits to just fosters, not admins/owners.
	{
		if(!$strict && $this->admin()) { return true; }
		$rid = $this->id();
		if(empty($rid)) { return false; }
		$user = $this->user(); #
		$path = "/RescueFoster[rescue_id=$rid]/rescue_id";
		return !empty(Set::extract($path,$user));
	}

	function mine()
	{
		if($this->user("Rescue.id")) # My PRIMARY rescue, as the owner.
		{
			return $this->user("Rescue"); # For access to hostname,etc
		}
	}

	function can_edit($data=null) # If we dont care about some users modifying inline items from others, just dont pass the object w/user_id
	{
		$me = $this->me();
		if(empty($me)) { return false; }
		if(!$this->id()) { return false; }
		if($this->owner()) { return true; }
		$controller = $this->request->params['controller'];

		# Check restricted controllers.
		if(in_array($controller, $this->rescuer_controllers)) { return false; } # Already checked above. sensitive owner stuff only.
		if($this->admin()) { return true; } 
		if(in_array($controller, $this->admin_controllers)) { return false; } # Others cannot.

		return !empty($data) ? ($data['user_id'] == $me) : true; # All other controllers (ie news, adoptables, etc).
	}

	# Useful to go to homepage for dedicated site.
	function url($rescue = null, $url = '/', $pass_session=true) # DISABLE session passing for EMAILS
	{

		if(is_string($rescue)) # Only gave url,assume current rescue.
		{
			$url = $rescue;
			$rescue = null;
		}

		if(!empty($rescue['Rescue'])) { $rescue = $rescue['Rescue']; }  # Strip.

		if(empty($rescue))
		{
			echo 'EMPTY RESCUE, SPIT URL BACK';
			if(!$this->id()) { return $url; } # Nothing we can do.
			$rescue = $this->get();
		}
		if(!$this->dedicated($rescue))
		{
			$url['rescue'] = $rescue['hostname']; #  Add into url.
			return "http://".Configure::read("default_domain").Router::url($url);
		}

		# Else, dedicated, so try to handle proper url.
		if(!empty($rescue['domain']) && HostInfo::domain_ready($rescue['domain']))
		{
			$host = "http://{$rescue['domain']}";
		} else if(!empty($rescue['hostname'])) { 
			$host = "http://{$rescue['hostname']}.".Configure::read("default_domain");
		} else {
			return "http://".Configure::read("default_domain").Router::url($url); # Failsafe.
		}

		if($pass_session)
		{
			if(is_array($url))
			{
				$url['?'] = array(Configure::read("Session.cookie")=>session_id());
			} else {
				$url .= "?".Configure::read("Session.cookie")."=".session_id();
			}
		}


		return $host.Router::url($url);
	}
	function breeds() # OK for single dropdown?
	{
		Configure::load("breeds");
		$breeds = Configure::read("Breeds");
		return $breeds;
	}

	function breedsSpecies()
	{
		$breeds = $this->breeds();
		$species = array_combine(array_keys($breeds),array_keys($breeds));

		return array($breeds,$species);
	}
}
