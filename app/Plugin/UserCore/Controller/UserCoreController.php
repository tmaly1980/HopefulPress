<?php
App::uses('UserCoreAppController', 'UserCore.Controller');
class UserCoreController extends UserCoreAppController {
	var $uses = array('User');
	var $home = "/";
	var $loginHome = "/admin";

	var $allowed = array('session','forgot','invite');

	var $notifyManagers = false; # On user account adds, etc.
	var $superkey = 'is_god';

	var $autoinvite = 1; # Send email when account is created.

	var $userModels = array('User'); # May have additional.

	#var $actsAs = array('UserCore'); # Shared between users and members!

	# XXX TODO way to use CORE views if app controller does not have implemented???
/* Actions */
	function whoami()
	{
		header("Content-type: text/plain");
		print_r($_SESSION);
		exit(0);
	}
	function reset() # Clears session.
	{
		#$newsid = md5(uniqid(time(),true));
		#$this->Session->id($newsid);

		#$this->setFlash("Session reset");
		$this->Session->destroy();
		#$this->Cookie->destroy();
		$this->redirect($this->referer($this->home));#!empty($this->members?"/members":"/"));
	}
	function internal($disable = false)
	{
		if($disable)
		{
			$this->setFlash("Tracking enabled/included");
			$this->Session->delete("internal");
		} else {
			$this->setFlash("Tracking disabled/excluded");
			$this->Session->write("internal",true);
		}

		$this->redirect($this->referer("/"));
	}

	function unauthorized()
	{
		$redirect = $this->Auth->loginRedirect;
		if(empty($redirect)) { $redirect = "/"; }
		$this->setError("Sorry, you're not authorized to view that page", $redirect);
	}

	function debug()
	{
		$current = $this->Session->read("debug");
		$this->Session->write("debug", !$current);
		$this->Session->setFlash($current ? "Debug disabled" : "Debug enabled");
		$this->redirect($this->referer($this->home));
	}

	/*
	function badauth($email) # Log in as a bogus user from some other site to debug redirect loop.
	{
		$this->{$this->modelClass}->autosite = false;
		$user = $this->{$this->modelClass}->findByEmail($email);
		error_log("BAD USER+".print_r($user,true));
		$this->Auth->login($user['User']);
		$this->redirect("/admin");
	}
	*/

	function session() # Debug 
	{
		header("Content-Type: text/plain");
		print_r($this->Session->read());
		exit(0);
	}

	function admin_session() { return $this->setAction("session"); }
	function manager_session() { return $this->setAction("session"); }

	public function index() {
		$this->{$this->modelClass}->recursive = 0;
		$this->set('users', $this->paginate());
	}

	public function view($id = null) {
		$this->set('user', $this->read($id));
	}

/* Actions */

	public function admin_index() {
		$this->model()->recursive = 0;
		$this->set($this->things(), $this->model()->findAll(array("{$this->modelClass}.email IS NOT NULL AND {$this->modelClass}.email != ''")));
		$this->set("site_owner", $this->model()->read(null, $this->Multisite->get("user_id")));
	}

	public function admin_view($id = null) {
		$this->redirect(array('action'=>'index'));
	}

	function admin_disable($id)
	{
		$user = $this->model()->read(null, $id);
		$thing = $this->thing();
		$things = $this->things();
		if(empty($user))
		{
			return $this->setFlash("Sorry, that $thing was not found.", array('action'=>'index'));
		}
		$this->model()->saveField("disabled", date("Y-m-d H:i:s"));
		$prefix = Configure::read("members_only") ? "admin_members" : "admin";
		$modelClass = $this->modelClass;
		$this->setFlash("The $thing account for <b>{$user[$modelClass]['email']}</b> has been disabled and the $thing will not be able to sign in again until it is re-enabled. If you've made a mistake, you can <a href='/$prefix/$things/enable/$id' class='color green'>re-enable</a> this account.", array('action'=>'index'));
	}

	function admin_enable($id)
	{
		$user = $this->model()->read(null, $id);
		$thing = $this->thing();
		if(empty($user))
		{
			return $this->setFlash("Sorry, that $thing was not found.", array('action'=>'index'));
		}
		$this->model()->saveField("disabled", null);
		$modelClass = $this->modelClass;
		$this->setFlash("The $thing account for <b>{$user[$modelClass]['email']}</b> has been re-enabled. If you've made a mistake, you can <a href='/admin/users/disable/$id' class='color red'>disable</a> this account again.", array('action'=>'index'));
	}

	# Links to re-send invitation
	function invite_email()
	{
		$this->_send_invite($this->me());
		$this->redirect(array('action'=>'index'));
	}

	function admin_invite_email($id)
	{
		$this->_send_invite($id);
		$this->redirect(array('action'=>'index'));
	}
	#################

	function invite($email = null,$code = null) { # Must be public
		if(empty($email) || empty($code))
		{
			$this->setFlash("Sorry, that link is invalid.", array('action'=>'login'));
		}

		$user = $this->model()->first(array("{$this->modelClass}.email"=>$email,'invite'=>$code));

		if(empty($user)) { 
			$this->setFlash("Sorry, that link is no longer valid.", array('action'=>'login'));
		} else { # Good
			# Log in, ask for password.

			/* since forgot password, always askk to change!!! 

			if(!empty($user[$this->modelClass][$passwordField])) # Password already set, just got to /admin
			{
				$user[$this->modelClass]['invite'] = null;
				$this->model()->id = $user[$this->modelClass]['id'];
				$this->model()->saveField('invite',null); # Clear invite now.

				$this->Auth->login($user);#[$this->modelClass]);
				# Do this after invite cleared so session no longer contains

				#error_log("GOING TO /admin");
				$this->redirect($this->loginHome);
			} else {
			*/
				$this->Auth->login($user);#[$this->modelClass]); # Methinks I need to get closer.

				#error_log("GOING TO /initialize");
				# Delay clearing invite only until once they have password set.
				# Force to blank page just asking for setting password.
				#$prefix = ($this->params['controller'] == 'members') ? "members" : "admin";

				$this->redirect( array('action'=>'initialize') );
			//}
		}
	}

	function admin_webmail()
	{
		$domain = $this->site("domain");
		$email_enabled = $this->site("email_enabled");
		if(empty($domain) || empty($email_enabled))
		{
			$this->setWarn("Webmail is not currently enabled, please talk to your administrator.", "/admin");
		}
		if($username = $this->Auth->user("username"))
		{
			$this->redirect("/mail/src/login.php?loginname=$username@$domain"); # Go to webmail, now.
		} 

		if(!empty($this->request->data['User']['username'])) # Registering username
		{
			if($this->{$this->modelClass}->save($this->request->data))
			{
				$username = $this->request->data['User']['username'];
				$this->redirect("/mail/src/login.php?loginname=$username@$domain"); # Go to webmail, now.
			} else {
				$this->setError("Could not save your email username");
			}
		} else if($this->site("email_register_manual")) { 
			# Self-register not allowed.
			$this->setWarn("Please contact your administrator to create an email username for you.", "/admin");
		}
	}

	function _post_initialize($oldUser = null, $user = null) { return; } # App defined.

	function initialize()
	{
		#print_r($this->Session->read("Auth.User"));

		$oldUser = $this->Auth->user(); # For reference.

		if(!empty($this->request->data))
		{
			
			$this->request->data[$this->modelClass][$this->model()->primaryKey] = $me = $this->me();
			if(empty($me))
			{
				return $this->setError("Sorry, you don't seem to be logged in: ".print_r($this->Session->read(),true));
			}
			$this->request->data[$this->modelClass]['invite'] = null; # Clear.

			# Password MUST be set (if form field present!)
			if($this->model()->save($this->request->data))
			{
				$user = $this->model()->read();
				$this->Auth->login($user);#[$this->modelClass]); # Refresh session.
				$this->_post_initialize($oldUser, $user); # App level definition
				$this->redirect($this->loginHome);#setFlash("Your account has been created", "/admin");
				# Should have tip with first-time login stuff... how to login again...
			} else {
				$this->setWarn("Could not save your account information: ".$this->model()->errorString());
			}
		}
		$this->request->data = $this->model()->read(null, $this->me()); #Session->read("Auth");
		# Info must be complete.
	}

	function _account() # Set own account/password.
	{
		$me = $this->me();
		if(!empty($this->request->data))
		{
			if($this->user("User.manager")) { $this->model()->autosite = false; } # Don't accidentally set site_id for managers.
			$this->model()->id = $me;
			if($this->model()->save($this->request->data))
			{
				$user = $this->model()->read();
				#$this->Auth->login($user['User']); # Update session.
				# This might be ok XXX
				$this->Auth->login($user);#[$this->modelClass]); # Update session.
				$redirect = $this->Auth->accountSaveRedirect;
				error_log("ACTSAVE_REDIR=$redirect");
				if($redirect === true) { 
					$redirect = $this->Session->read("goto"); 
					$this->Session->delete("goto"); 
				} # True = referer; whatever page was on
				error_log("REF=".print_r($redirect,true));
				$this->setSuccess("Your account has been updated",$redirect);
				return true;
			} else {
				$this->setError("Could not save your account information.");
				return false;
			}
		} else { # Only load if form wasnt submitted/failed.
			$this->request->data = $this->{$this->modelClass}->read(null, $this->Auth->me()); # Pull other stuff than user,  ie Photo
			if(!$this->Session->read("goto"))
			{
				$this->Session->write("goto", $this->referer());
			}
			return false;
		}
		# true = submitted, false = viewing
	}

	function login() # User account info is saved to Auth.User.User.FIELD .... 
	{
		error_log("CALLED AC:LOGIN...");
	
		# may want to force prefix (per app)
		$userField = $this->Auth->authenticate['Form']['fields']['username'];
		$passwordField = $this->Auth->authenticate['Form']['fields']['password'];

		# XXX implement multiple models here.... User, TechUser, etc.

		if(!empty($this->request->data))
		{
			error_log("PROCESSING...");
			if(!empty($this->managerLoginField))
			{
				$this->{$this->modelClass}->bypassSiteField = $this->managerLoginField;
			}
			# Get WHOLE record (in case related records matter)
			$user = null;
			foreach($this->userModels as $model=>$conditions) # We can pass additional "active" account parameters, etc.
			{
				if(!is_array($conditions))
				{
					$model = $conditions;
					$conditions = array();
				}
				$this->loadModel($model);
				list($plugin,$model) = pluginSplit($model);
				$conditions["{$model}.$userField"] = $this->request->data['User'][$userField];
				$conditions[$passwordField] = $this->$model->hash($this->request->data['User'][$passwordField]);

				if($this->$model->hasField("disabled"))
				{
					$conditions[] = "({$model}.disabled IS NULL OR {$model}.disabled = 0)";
				}

				$user = $this->$model->find('first', array('recursive'=>2,'conditions'=>$conditions));
				if(!empty($user)) { 
					$user['User'] = $user[$model]; # Make compatible.
					break;
				}
			}

			error_log("LOGGING IN=".print_r($user,true));
			if(!empty($user) && $this->Auth->login($user))#['User']))
			{
				$this->postLogin(); # Implement custom redirect here?
			} else {
				$this->setError(!empty($this->Auth->errorMsg) ? $this->Auth->errorMsg : "Email or password is incorrect");
			}
		}
		return false;
	}

	function postLogin() # After successful login
	# Per app, check for account status (paid, trial, expired, etc)
	{
		error_log("UC:POSTLOGIN");
		if($this->Components->enabled('Tracker'))
		{
			$this->Tracker->hide_session();
		}

		if($this->{$this->modelClass}->hasField("last_login")) # Track user logins
		{
			$timestamp = date("Y-m-d H:i:s");
			$this->{$this->modelClass}->updateAll( # will allow for expressions
				array(
					"last_login"=>"'$timestamp'",
					"login_count"=>" login_count+1 "
				), array('User.id'=>$this->Auth->user('id'))
			);
		}

		return $this->redirect($this->Auth->redirectUrl());
	}

	function manager_login()
	{
		if($this->model()->Behaviors->enabled("Multisite"))
		{
			$this->model()->autosite = false;
		}
		$this->setAction("login");
	}

	function logout($redirect = true)  # Alternative url.
	{ # Per app, may want to clear session_id (if tracking 
		$this->setInfo("You have been logged out");
		# Clear user_session_id
		if($this->Components->enabled("Tracker"))
		{
			$this->Tracker->logout(); # Clears user_session_id ? 
		}
		$url = $this->Auth->logout();
		if($redirect === false) { return; } # Do something else.

		if($redirect !== true) { $url = $redirect; } #
		$this->redirect($url);
	}

	function forgot()
	{
		$userField = Configure::read("User.userField");
		if(empty($userField)) { $userField = 'email'; }

		if(!empty($this->request->data[$this->modelClass][$userField]))
		{
			$user = $this->model()->first(array("{$this->modelClass}.$userField"=>$this->request->data[$this->modelClass][$userField]));
			if(!empty($user))
			{
				$this->model()->id = $user[$this->modelClass][$this->model()->primaryKey];
				$this->model()->saveField("invite", $this->model()->random_chars(16));
				$user = $this->model()->read();
				$thingVar = $this->thingVar();

				$this->sendUserEmail($user, "Create a new password", "users/forgot", array($thingVar=>$user));
				$this->setInfo("A message has been sent to your email with further instructions.", array('action'=>'login'));
			} else {
				$this->setError("Sorry, we couldn't find an account with that email. Enter in a different email, or contact your administrator.");
			}
		}
	}
	
	function _index()
	{
		$cond = array();
		if($this->{$this->modelClass}->hasField("manager")) # Hide ME
		{
			$cond["manager"] = 0;
		}
		$this->set("users", $this->{$this->modelClass}->findAll($cond));
	}

	public function _edit($id = null) { # Any custom data/options should get called first.
		if($id) { $this->check($id); }
		$userField = Configure::read("User.userField");
		if(empty($userField)) { $userField = 'email'; }
		$oldUser  = !empty($id) ? $this->model()->read(null, $id) : null;

		if (!empty($this->request->data)) { #$this->request->is('post') || $this->request->is('put')) {
			if ($this->model()->saveAll($this->request->data)) {

				# XXX TODO If username was recently set/changed and email system enabled, send an email
				if( !empty($this->request->data[$this->modelClass]['username']) && (
					empty($oldUser[$this->modelClass]['username']) || $this->request->data[$this->modelClass]['username'] != $oldUser[$this->modelClass]['username'] || !empty($this->request->data[$this->modelClass]['webmail_instructions'])
				))
				{
					$this->_send_webmail_instructions($id);
				}

				if((empty($id) && $this->autoinvite) || !empty($this->request->data[$this->modelClass]['invite'])) { # Creating OR explicit notification.
					$id = $this->{$this->modelClass}->field($this->{$this->modelClass}->primaryKey,array("{$this->modelClass}.$userField"=>$this->request->data[$this->modelClass][$userField])); # Better way since saveAll() doesnt keep ->id
					$this->_send_invite($id);
				} else {
					$this->setSuccess("The user account has been ".(!empty($id)?"updated":"created"),array('action'=>'index'));
				}

				$this->redirect(array('action'=>'index'));
			} else {
				$this->setError("Could not ".($id?"update":"create"). " the user account: ".$this->{$this->modelClass}->errorString());
			}
		} else if(!empty($id)) {
			$this->request->data = $this->model()->read(null, $id);
		}

		$this->set("autoinvite", $this->autoinvite);
	}

	function _send_webmail_instructions($id)
	{
		if(!method_exists($this,'site')) { return; } # irrelevant.
		if(!$this->site("email_enabled")) { return; }
		if(!($domain = $this->site("domain"))) { return; }

		$emailField = Configure::read("User.emailField");
		if(empty($emailField)) { $emailField = 'email'; }

		$this->model()->id = $id;

		$user = $this->model()->read(null, $id);
		$email = $user[$this->modelClass][$emailField];
		$username = $user[$this->modelClass]['username'];

		$sitename = Configure::read("site_title");
		$thing = $this->thing();
		$things = $this->things();
		$thingVar = $this->thingVar();

		# Works with account types OTHER than User, ie Member - email template will be stored separately

		$this->sendUserEmail($user, "Your webmail account".(!empty($sitename)?" for $sitename":""), "$things/webmail", array('user'=>$user,$thingVar=>$user,'username'=>$username,'domain'=>$domain));
	}

	function _send_invite($id)
	{
		$emailField = Configure::read("User.emailField");
		if(empty($emailField)) { $emailField = 'email'; }

		# Assigns an invite code.
		$this->model()->id = $id;
		$this->model()->saveField("invite", $this->model()->random_chars(16));

		$user = $this->model()->read(null, $id);
		$email = $user[$this->modelClass][$emailField];

		$sitename = Configure::read("site_title");
		$thing = $this->thing();
		$things = $this->things();
		$thingVar = $this->thingVar();

		# Works with account types OTHER than User, ie Member - email template will be stored separately

		$this->sendUserEmail($user, "A $thing account has been created for you".(!empty($sitename)?" on $sitename":""), "$things/created", array('user'=>$user,$thingVar=>$user));
		if($this->notifyManagers)
		{
			$this->sendManagerEmail("New $thing $email".(!empty($sitename)?" on $sitename":""), "$things/created", array($thingVar=>$user));
		}

		if($this->model()->hasField("invited"))
		{
			$this->model()->saveField("invited", date('Y-m-d H:i:s'));
		}

		$this->setSuccess("The $thing account has been created and an email has been sent to <b>$email</b> with further instructions.",array('action'=>'index'));
	}

	function admin_settings() # Set a user's preference with something...
	{ # Only bother with in admin, not members
		if(!empty($this->data))
		{
			# FAKED, NEED TO GATHER REAL USER INFO.... XXX TODO
			$k = !empty($this->data['UserSetting']['field']) ? $this->data['UserSetting']['field'] : null;
			$v = !empty($this->data['UserSetting']['value']) ? $this->data['UserSetting']['value'] : null;
			# UserSetting->deleteAll(array('field'=>$k, 'user_id'=>$me)); # Remove old.
			# UserSetting->save($data);
			$this->Json->set("fake", "FAKE USER SETTING SET $k=>$v");
		}
		$this->Json->render();
	}

	function admin_dismiss() # Dismiss a box forever.
	{
		if(!empty($this->data))
		{
			#$dismiss = $this->UserDismissable->findByField($this->data['UserDismissable']['field']);
			if(!empty($dismiss)) 
			{
				$this->UserDismissable->id = $dismiss['UserDismissable']['id'];
			} # Replace.
			if(!$this->UserDismissable->save($this->data))
			{
				$this->Json->set("error", "Could not save setting"); 
			} else {

			}
		}
		$this->Json->render();
	}

	function admin_session_dismiss() # Dismiss a box until next login. Stored in session. 'Remind me later'
	{
		if(!empty($this->data))
		{
			$field = $this->data['SessionDismissable']['field'];
			$this->Session->write("session_dismissed.$field", true);
		}
		$this->Json->render();
	}

	function _delete($id)
	{
		if($this->{$this->modelClass}->delete($id))
		{
			return $this->setSuccess("The user account has been deleted",array('action'=>'index'));
		} else {
			return $this->setError("Could not delete user account. ".$this->{$this->modelClass}->errorString(),array('action'=>'index'));
		}
	}

	/*
	function _import() # XXX needs fixing for generic use
	{
		if(!empty($this->request->data['User']['file']))
		{
			if(empty($this->request->data['User']['confirm'])) # Ask to confirm
			{
				$this->Upload->content_only(true); 
				$csv = $this->Upload->upload($this->request->data['User']['file']);
	
				$delim = ","; # Tab or comma
	
				$lines = split("\n", $csv);
	
				# First line is keys, ie
				# Email, LastName, FirstName
				$keyline = array_shift($lines);
				$keys = split($delim, $line);
	
				$users = array('User'=>array());
	
				foreach($lines as $line)
				{
					$row = split($delim, $line);
	
					$rowdata = array_combine($keys, $row);
	
					$users['User'][] = $rowdata;
				}

				if(empty($users))
				{
					return $this->setError("Unable to find any users in file. Please check your file and make sure entries are one per line, with fields separated by commas.");
				}
				$this->set("growers", $this->{$this->modelClass}->Grower->find('list')); # Let them specify the grower right away

				// **** dealing with problem of signup email BEFORE a grower is assigned....
				// 
				// checkbox to email user of account, with timestamp of if/when they were (or werent) - to know that SHOULD
				// add checkbox to bulk import form, next to grower dropdown selectors (SignupNotify flag in CSV)


				# Display confirmation page.
				$this->request->data = $users;
				$this->view = 'admin_import_confirm'; # Stored as multiline form (so can tweak)
			} else { # Do actual import
				if($this->{$this->modelClass}->saveAll($users))
				{
					# now do email notifications, if asked..
					foreach($users['User'] as $user)
					{
						if(!empty($user['SignupNotify']))
						{
							$id = $this->{$this->modelClass}->first(array('Email'=>$user['Email']));
							$this->_send_invite($id);
							$emailsNotified[] = $user['Email'];
						}
					}

					if(!empty($emailsNotified))
					{
						return $this->setSuccess("Import complete and emails sent to: ".join(", ", $emailsNotified)."<br/><br/>Make sure any grower accounts have growers assigned to them to be able to sign in.", array('action'=>'index'));
					} else {
						return $this->setSuccess("Import complete", array('action'=>'index'));

					}
				} else {
					return $this->setError("Import failed: ".$this->{$this->modelClass}->errorString());
				}
			}
		}

	}
	*/
}
