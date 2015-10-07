<?
# Simplify auth and keep logic minimal per app
App::uses('AuthComponent','Controller/Component');

class UserAuthComponent extends AuthComponent # PASS THRU!
{
	var $authError = 'Please sign in to continue';
	var $flash = array(
		'element'=>'alert',
		'key'=>'auth',
		'params'=>array(
			'plugin'=>'BoostCake',
			'class'=>'alert-warning',
			'icon'=>'warning-sign'
		)
	);
	var $userModel = 'User'; # Can be something else. ie Member
	var $loginAction = array('controller'=>'users','action'=>'login');
	var $loginRedirect = array('controller'=>'users','action'=>'home');
	var $accountSaveRedirect = true; # true = referer; Where (if anywhere) to go after user's self account saved...
	var $unauthorizedRedirect  = "/users/unauthorized";
	var $managerField = null; # Flag for users who bypass site restriction.
	var $authenticate = array(
		'Form'=>array(
			'fields'=>array()
		)
	);
	var $publicAllowed = true; # Whether content not under /admin (etc) public; is whole app password restricted?
	var $controller = null;
	var $authorize = 'Controller'; # isAuthorized is in AppController
		# Controller needs to restrict admin prefix access based on account type
		# But we can also handle basic prefix mappings below

	var $prefixes = array( # Map prefix to user access
		# 'prefix' => 'field', ie:
		# 'admin' => 'admin' # access to /admin if admin flag set in user account
		# 'user' => true # access to /user for all logged-in users
		# '' => true # access to / (whole site) requires logging in
		# 'owner' => false, # Only site owner (false) has access to /owner/*
	);

	function initialize(Controller $controller)
	{
		$this->controller = $controller;
		$userModel = Configure::read("User.userModel");
		if(!empty($userModel)) { $this->userModel = $userModel; }


		if(empty($this->controller->{$this->userModel}))
		{
			throw new Exception("{$this->userModel} Model not initialized in main controller...");
		}

		if(empty($this->authenticate['Form']['fields']))
		{
			$userField = Configure::read("User.userField");
			$passwordField = Configure::read("User.passwordField");
			if(!empty($userField)) { $this->authenticate['Form']['fields']['username'] = $userField; }
			if(!empty($passwordField)) { $this->authenticate['Form']['fields']['password'] = $passwordField; }
		}
		# Default email/password fields
		if(empty($this->authenticate['Form']['fields']['username'])) {
			$this->authenticate['Form']['fields']['username'] = 'email';
		}
		if(empty($this->authenticate['Form']['fields']['password'])) {
			$this->authenticate['Form']['fields']['password'] = 'password';
		}

		return parent::initialize($controller);
	}

	function startup(Controller $controller) 
	# May have other things that need to 
	{
		$this->permit();
		$this->checkUserModified(); # Keep user session up-to-date if modified.

		Configure::write("user_id", $this->me()); # Saved for autouser

		parent::startup($controller);
	}

	function isAuthorized($user = null, CakeRequest $request = null)
	{
		if(!empty($this->prefixes))
		{
			$prefix = !empty($request->params['prefix']) ? $request->params['prefix'] : ''; # Check prefix access based on user
			# Could be blank if for public site


			# We can password protect entire site by setting ''=>true

			$field = isset($this->prefixes[$prefix]) ? $this->prefixes[$prefix] : null;

			if($field === true) # All users
			{
				return !empty($user['User']['id']);

			} else if ($field === false) { # Site owner only - assumes CurrentSite.Site.user_id in Configure (otherwise we can create a 'owner' flag in record)

				$owner_id = Configure::read("CurrentSite.Site.user_id");
				return (!empty($owner_id) && $owner_id === $user['User']['id']) || $user['owner']; // may be hack from anonymous site creation

			} else if (!empty($field)) { # Field in user record. 

				return !empty($user['User'][$field]);
			}

			# Otherwise, prefix tests did not apply

		}

		return parent::isAuthorized($user,$request); # Default, probably goes to Controller::isAuthorized()
	}

	function permit()
	{
		if(!empty($this->controller->allowed)) # Defined in controller
		{
			$this->allow($this->controller->allowed);
		}

		if(!empty($this->controller->denied)) # Defined in controller
		{
			$this->deny($this->controller->denied);
		}

		# Restrict access based on prefix mappings.
		$prefix = !empty($this->request->params['prefix']) ? $this->request->params['prefix'] : ''; # Might be blank, ie entire site
		if($prefix && isset($this->prefixes[$prefix])) # Defined.
		{
			$this->deny();
		} else { # No prefix or other prefixes NOT defined in config
			#if(!empty($this->request->params['prefix']) || $this->publicAllowed) # We can deny whole site if password protected.
			if(empty($this->request->params['prefix']) && $this->publicAllowed) # We can deny whole site if password protected.
			{
				$this->allow(); # Anywhere else.
			}
			# Consider that Members component calls it's OWN deny()
		}
	}

	function checkUserModified() # Refresh in case details/status/etc modified
	{
		$pk = $this->controller->{$this->userModel}->primaryKey;
		$uid = $this->user("{$this->userModel}.$pk");
		if(!empty($uid))
		{
			$cached_modified = $this->user("{$this->userModel}.modified");
			$this->controller->{$this->userModel}->id = $uid;
			$last_modified = $this->controller->{$this->userModel}->field('modified');
			if(empty($last_modified) || strtotime($cached_modified) < strtotime($last_modified)) # UPDATE
			{ 
				$user = $this->controller->{$this->userModel}->read(null, $uid); # Re-read record since modified.
				$this->login($user); # UPDATE session
			}
		}
	}

	function id()
	{
		if(empty($this->controller)) { return; } # Error handling
		return $this->user($this->controller->{$this->userModel}->primaryKey); # Compatible with multiple models. User.UserID, etc.
	}

	function me()
	{
		return $this->id();
	}

	function beforeRender(Controller $controller)
	{
		if(empty($controller)) { return; } # Error.

		$controller->set("current_user", $this->user()); #  returns array within Session.Auth.User
		$controller->set("me", $this->me());
	}



}
