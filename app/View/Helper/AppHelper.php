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
	var $helpers = array('Session','Html','Form','Multisite.Site');
	# DO NOT SET SUB-HELPERS IN INDIVIDUAL HELPERS UNLESS COMPLETE LIST!

	# ACLS GO HERE.... if not listed as special, will give access to any logged in user...
	var $site_owner_controllers = array(
		'sites',
		'setup',
	);
	var $admin_controllers = array(
		'homepages',
		'about_pages',
		'about_page_bios',
		'contact_pages',
		'contacts',
		'volunteer_overviews',
		'foster_overviews',
		'adoption_overviews',
		'foster_forms',
		'adoption_forms',
		'volunteer_forms',
	);

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

	# If no data, assume current controller.
	function can_edit($data = null)  # For checking write access, ie owner, admin, or site_owner
	{ # Assumes $something['Model'] is passed, so immediate keys accessible.

		if(!$this->me()) { return false; }

		if($this->is_site_admin()) { return true; }

		# Now look at special controllers
		$controller = $this->request->params['controller'];
		# Assume current controller.

		# Project access.... 
		if(!empty($pid) && $controller == 'projects')
		{
			return $this->is_project_user();
		} else if (!empty($pid) && in_array($controller, $this->projectable_controllers)) { 
			if($this->is_project_admin())
			{
				return true;
			} else if(isset($data['user_id'])) {  # Existing record.
				return ( $data['user_id'] != $this->me() ); 
				# Owner can edit stuff
			} else {
				return $this->is_project_user(); # If not an existing record, project users can add.
			}
		}

		# Check restricted controllers.
		if(in_array($controller, $this->admin_controllers)) { return $this->user("admin"); }
		if(in_array($controller, $this->site_owner_controllers)) { return $this->Site->is_owner(); }


		# Look at record owner.
		if(isset($data['user_id'])) { return ( $data['user_id'] != $this->me() ); }

		return true; # Otherwise.
	}
}
