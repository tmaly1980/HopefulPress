<?php

App::uses('CoreAppHelper', 'Core.View/Helper');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends CoreAppHelper {
	var $helpers = array('Session','Html','Form','Rescue');
	# DO NOT SET SUB-HELPERS IN INDIVIDUAL HELPERS UNLESS COMPLETE LIST!

	# ACLS GO HERE.... if not listed as special, will give access to any logged in user...

	function is_project_owner()
	{
		$project = Configure::read("project");
		if(empty($project)) { return false; }
		return $project['Project']['user_id'] == $this->me();
	}

	function is_project_admin() # Owner or admin
	{
		$me = $this->me();
		$project = Configure::read("project");
		if(empty($project)) { return false; }
		$projectAdminIDs = Set::extract("/ProjectUser[admin=1]/user_id", $project);
		return ($project['Project']['user_id'] == $me) || in_array($me, $projectAdminIDs);
	}

	function is_project_user() # Owner, admin or member
	{
		$me = $this->me();
		$project = Configure::read("project");
		if(empty($project)) { return false; }
		$projectUserIDs = Set::extract("/ProjectUser/user_id", $project);
		return ($project['Project']['user_id'] == $me) || in_array($me, $projectUserIDs);
	}

	function is_admin() { return $this->is_site_admin(); }

	function is_site_admin()
	{
		if($this->is_site_owner()) { return true; }
		if($this->user("admin")) { return true; }
		return false;
	}

	function is_site_owner()
	{
		if($this->is_manager()) { return true; }
		if($this->Site->is_owner($this->me())) { return true; }
		return false;
	}
	function site_owner() { return $this->is_site_owner(); }

	function is_manager() { return $this->user("manager"); }
	function manager() { return $this->is_manager(); }

	function rescuer() { return $this->user("rescuer"); } # This person has or could have a rescue.
	function volunteer() { return $this->user("volunteer"); } # This person is or could be a volunteer.

	# For checking write access, ie rescuer, admin, volunteer, etc
	function can_edit($data=null)
	{ # Assumes $something['Model'] is passed, so immediate keys accessible. Rarely/never needed.
		if(empty($data)) # Assume current record, if set.
		{ # ie profile page. might be mine.
			$data = $this->Form->data();
		}

		if(!$this->me()) { return false; }
		if($this->manager())  { return true; }

		# Now look at special controllers
		$controller = $this->request->params['controller'];

		# Current section, current controller, or a specific record in question.
		if($this->Rescue->id()) # In a rescue.
		{
			return $this->Rescue->can_edit($data); # Better idea.

		} else { # Not in rescue, restrictions to editing a record depends upon owner of record, otherwise assume we're adding records.
			# If passed record, check owner.

			# Else, assume record is in view var, check, ie current page/listing for editability.

			return !empty($data) ? ($data['user_id'] == $me) : true; # General user-only section or public page where users can add content (ie listings etc)
			# Not specific to a volunteer vs rescuer, etc role.
		}
		# If a page is meant for volunteers (or rescues) to add records, prefer using $this->Html->volunteer() or $this->Html->rescuer() instead.
		# Otherwise we assume all users are created equal. If no record passed.

	}
}
