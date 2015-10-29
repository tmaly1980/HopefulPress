<?
App::uses('UsersController', 'Controller');
class RescueVolunteersController extends AppController #UsersController # Easy inherit.
{
	var $uses  = array('RescueVolunteer','Volunteer','VolunteerForm','Adoptable');
	var $thing = 'volunteer';
	var $ucThing = 'Volunteer';
	var $rescueThing = 'RescueVolunteer';
	# Could be overrwritten by foster.

	# Applying to a specific rescue.

	function index()
	{
		$this->redirect(array('controller'=>"{$this->thing}_page_indices"));
	}

	# Eventually it won't be the rescue's responsibility to manage other {$this->thing} accounts, enable, send account invite, reset passwords, etc.
	function _invite($id)  # Setting to 'Active' alone might give them  access if they have an account, but regardless we should explicitly contact them.
	{
		# Either "a user account has been created for you" or "you have been granted access to"

		$this->{$this->rescueThing}->id = $id;
		$person = $this->{$this->rescueThing}->read();
		$email = $person["{$this->ucThing}"]['email'];
		
		$this->Rescue->recursive = -1;
		$this->Rescue->id = $person["{$this->rescueThing}"]['rescue_id'];
		$rescue = $this->Rescue->read();

		$vars = array(
			'rescue'=>$rescue,
			$this->thing=>$person
		);

		Configure::write("site_title", $rescue['Rescue']['title']);  # For emails...

		if($user_id = $person[$this->ucThing]['user_id']) { # User already assigned (to profile).
			$user = $this->User->read(null,$user_id);

		} else if (($user = $this->User->findByEmail($email)) && !empty($user)) { # Already signed up.
			# that's it

		} else {  # Create  user account...
			if(!$this->User->save($person[$this->ucThing])) # Email, first_name, last_name
			{
				return $this->setError("Could not create user account for {$this->thing}");
			}
			$user = $this->User->read();
		}
		$this->User->id = $user_id = $user['User']['id'];

		error_log("USER_ID=$user_id, IS=".print_r($user,true));

		# MIGHT need to set invite code, IF never signed in before (whether existing or not)
		# IF they've "forgotten" their password, they should click on 'forgot password' themselves.
		if(empty($user['User']['password']))
		{
			$this->User->saveField("invite", $this->User->random_chars(16));

			$user = $this->User->read(); # Reload, with invite code.
		} else {
			$vars['existing'] = 1;
		}
		$vars['user'] = $user;

		# Assign that user_id to the {$this->thing} account...
		$this->{$this->rescueThing}->saveField("user_id",$user_id);

		# Save profile too, so they can modify it, use it later, etc.
		$this->{$this->rescueThing}->{$this->ucThing}->id = $person[$this->ucThing]['id'];
		$this->{$this->rescueThing}->{$this->ucThing}->saveField("user_id",$user_id);

		# **** how do we give them a url that'll go directly to the rescue once  they log in (ie if not dedicated url) ???????

		# pass  'redirect' field, that gets saved to Auth.redirect


		$this->sendUserEmail($user_id, "{$this->ucThing} access","rescue/{$this->thing}_invited",$vars);

		$name = !empty($user['User']['first_name']) ? $user['User']['first_name'] : "The user";
		$email = $user['User']['email'];
		return $this->setSuccess("$name has been emailed instructions to $email for signing in",array('action'=>'index'));
	}

	function admin_edit($id=null)
	{
		$this->edit($id);
		if(!empty($this->request->data))
		{

			# Don't save to user account
			if($this->{$this->rescueThing}->saveAll($this->request->data))
			{
				if(!empty($this->request->data[$this->rescueThing]['invite']))
				{
					return $this->_invite($id);
				}
				$this->setSuccess("The {$this->thing} application has been ".($id?"updated. ":"submitted. "), array('action'=>'index'));
			} else {
				$this->setError("Could not submit application. ".$this->{$this->ucThing}->errorString());

			}
		} 

		if(!empty($id))
		{
			$this->request->data = $this->{$this->rescueThing}->read(null, $id);
		}

		$thingForm = $this->ucThing."Form";
		$this->set("{$this->thing}Form", $this->{$thingForm}->singleton());
		$this->set("adoptables", $this->Adoptable->find('list',array('conditions'=>array('Adoptable.status'=>'Available'))));
		$this->set("statuses", $this->{$this->rescueThing}->dropdown('statuses'));
	}

	function edit()
	{

		if(!empty($this->request->data))
		{
			# Don't save to user account if already signed in as a volunteer/foster with the rescue. ie doing for others.
			if($this->rescue_member())
			{
				$this->{$this->rescueThing}->autouser = false;
				$this->{$this->rescueThing}->{$this->ucThing}->autouser = false;

				$email = !empty($this->request->data[$this->ucThing]['email']) ?
					$this->request->data[$this->ucThing]['email'] : null;

				# Instead, find user account if any and link 
				# user_id is always hidden so we know if set or not.
				if(empty($this->request->data[$this->rescueThing]['user_id']) && $email)
				{
					if($user_id = $this->User->field('id',array('email'=>$email)))
					{
						# Link user to rescue_volunteer so their status is shown.
						$this->request->data[$this->ucThing]['user_id'] = $user_id;
							
						# Don't assign Volunteer profile if they already have one.
						if(!$this->{$this->ucThing}->count(array('user_id'=>$user_id)))
						{
							$this->request->data[$this->ucThing]['user_id'] = $user_id;
						}
					}
				}
			}

			# XXX even if they have a full volunteer/etc application finished, we still should probably ask for 
			# "what you'd like to contribute/what you have to offer", etc for this specific instance.
			#
			if($this->{$this->rescueThing}->saveAll($this->request->data)) # Will save changes to {$this->thing} profile as needed.#
			{
				# Maybe later send to someone else with a specific role.
				$sendEmail = "send".$this->ucThing."Email";
				$this->$sendEmail($this->{$this->rescueThing}->read());
				$this->setSuccess("Your {$this->thing} application has been submitted. Someone will contact you shortly.", array('action'=>'index'));
			} else {
				$this->setError("Could not submit application. ".$this->{$this->rescueThing}->errorString());

			}
		} 

		$thingForm = $this->ucThing."Form";
		$this->set("{$this->thing}Form", $this->{$thingForm}->singleton());
		$this->set("adoptables", $this->Adoptable->find('list',array('conditions'=>array('Adoptable.status'=>'Available'))));
		$this->set("statuses", $this->{$this->rescueThing}->statuses);
	}

	function admin_delete($id)
	{
		$this->RescueVolunteer->delete($id);
		return $this->setSuccess("The volunteer/application has been deleted", array('action'=>'index'));
	}

	function admin_index()
	{
		$this->set("{$this->thing}s", $this->{$this->rescueThing}->find('all',array('conditions'=>array("{$this->rescueThing}.status"=>'Active'))));
		$this->set("offline{$this->ucThing}s", $this->{$this->rescueThing}->find('all',array('conditions'=>array("{$this->rescueThing}.status"=>'Active Offline'))));
		$this->set("applicants", $this->{$this->rescueThing}->find('all',array('conditions'=>array("{$this->rescueThing}.status"=>'Applied'))));
		$this->set("inactives", $this->{$this->rescueThing}->find('all',array('conditions'=>array("{$this->rescueThing}.status"=>'Inactive'))));
		$this->set("ignoreds", $this->{$this->rescueThing}->find('all',array('conditions'=>array("{$this->rescueThing}.status"=>'Ignored'))));
	}

	/* disabled.
	function admin_status($id)
	{
		if(!empty($this->request->data))
		{
			if($this->{$this->rescueThing}->save($this->request->data))
			{
				if(!empty($this->request->data[$this->rescueThing]['invite']))
				{
					# XXX TODO
					return $this->_invite($id);
					#$this->setSuccess("Status updated and the user has been sent an email with instructions for signing in",array('action'=>'index')); 
				}  else  {
					$this->setSuccess("Status updated",array('action'=>'index')); 
				}
			} else {
				$this->setError("Could not update status: ".$this->{$this->rescueThing}->errorString(),array('action'=>'index'));
			}
		}
		$this->request->data = $this->{$this->rescueThing}->read(null,$id);
		$this->set("statuses", $this->{$this->rescueThing}->statuses);
	}
	*/

	function admin_view($id=null)
	{
		$this->set("rescue{$this->ucThing}", $this->{$this->rescueThing}->read(null,$id));
	}
}
