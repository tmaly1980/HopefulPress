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

	function dedicated()
	{
		$plan = $this->get("plan");
		return in_array($plan,array('dedicated','unlimited'));
	}

	function hostname()
	{
		return $this->get("hostname");
	}

	function id() { return $this->get("id"); }

	function member() # Is current user a member  of the current rescue (has admin or volunteer access)
	{
		return $this->volunteer(); # Or admin or owner.
	}

	function rescuer() { return $this->owner(); }

	function  owner()
	{
		if(!$this->id()) { return false; }
		$me = $this->me();
		if(empty($me)) { return false; }
		return $this->get("user_id") == $me;
	}

	# ACL's stored in UserRescue, admin=>1 allows admin access.

	function admin($strict=false) # More than volunteer.
	{
		if(!$strict && $this->owner()) { return true; }
		$rid = $this->id();
		if(empty($rid)) { return false; }
		$rescues = $this->user("Rescues"); #
		if(empty($rescues)) { return false; }
		return Set::extract("/[id=$rid][admin=1]/user_id", $rescues);
	}

	function volunteer($strict=false) # true limits to just volunteers, not admins/owners.
	{
		if(!$strict && $this->admin()) { return true; }
		$rid = $this->id();
		if(empty($rid)) { return false; }
		$rescues = $this->user("Rescues"); #
		if(empty($rescues)) { return false; }
		return Set::extract("/[id=$rid][admin=0]/user_id", $rescues);
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
		if(in_array($controller, $this->rescuer_controllers)) { return false; } # Already checked above.
		if(in_array($controller, $this->admin_controllers)) { return $this->admin(); }
		return !empty($data) ? ($data['user_id'] == $me) : true; # All other controllers (ie news, adoptables, etc).
	}
}
