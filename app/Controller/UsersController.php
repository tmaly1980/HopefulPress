<?php
App::uses('UserCoreController', 'UserCore.Controller');
class UsersController extends UserCoreController {
# Password (tomas1234): 57534963b3a884145f020bde232cf6d07bef2d37304f31ab49b89551ee12c775
#
	var $uses = array('User');

	var $layout = 'admin';

	var $loginHome = '/'; # For post-initialize

	#var $superkey = "TechSupport";

	/* MOVE roles into flags.
	var $userModels = array('User',
		'Rescue.Volunteer'=>array('status'=>'Active'),
		'Rescue.Foster'=>array('status'=>'Active')
	);
	*/

	function rescuer_login() { 
		# If they clicked on 'create account', make it for them, if it doesn't exist.
		return $this->login(); 
	}
	# Add volunteer, etc...

	function admin_index()
	{
		if(!$this->me()) # No account yet.
		{
			$this->setInfo("Before you can create other user accounts, you'll need to create your own user account");
			$this->redirect("/users/initialize");
		}
		return $this->_index();
	}

	function admin_edit($id = null)
	{
		return $this->_edit($id);
	}
	
	function admin_delete($id = null)
	{
		return $this->_delete($id);
	}

	function admin_settings() # Change site owner, etc.
	{
		if(!empty($this->request->data['Site']['user_id']))
		{
			$new_owner = $this->request->data['Site']['user_id'];
			$previous_owner = $this->Multisite->get("user_id");
			if($previous_owner != $new_owner) 
			{
				$this->Multisite->saveField("user_id", $new_owner);
				$site = $this->Site->read();
				$user = $this->User->read(null, $new_owner);
				$vars = array('current_site'=>$site,'current_user'=>$user['User']);
				$this->sendUserEmail($new_owner, "You have been assigned as site owner", "sites/owner",$vars);
				$this->setSuccess("Site owner has been changed, and the user has been notified", array('action'=>'index'));
			}
			$this->redirect(array('action'=>'index'));
		}
		$this->set("users", $this->User->find('list'));
		$this->request->data = $this->Site->read();
	}

	function _post_initialize($oldUser = null, $user = null)
	{
		if(!empty($oldUser['owner']) && $this->me() && !$this->site("user_id")) # No site owner yet.
		{
			$this->Site->id = $this->get_site_id();
			$this->Site->saveField("user_id", $this->me());
			# Assign self as site owner.
		}
	}

	function user_account()
	{
		# XXX FIXME
		# updating my (manager) account should ignore site_id for ALL queries....
		# 
		/*
		$me = $this->me();
		$owner = $this->Auth->user("owner");
		if($this->_account()) # Processed.
		{
			# May have just created owner account - should redirect to /admin/users
			if(!$me && $owner)
			{
				$this->setSuccess("Your user account has been created. You'll be able to sign in using your email and password to update your site later. You can <a href='/admin/users/add'>add more users</a> or <a href='/'>go back to the main website</a>");
				$this->redirect("/admin/users");
			}
		}
		*/
		$this->_account();
	}

}
