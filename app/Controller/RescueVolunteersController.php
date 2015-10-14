<?
App::uses('UsersController', 'Controller');
class RescueVolunteersController extends AppController #UsersController # Easy inherit.
{
	var $uses  = array('RescueVolunteer','Volunteer','VolunteerForm','Adoptable');

	# Applying to a specific rescue.

	function index()
	{
		$this->redirect(array('controller'=>'volunteer_page_indices'));#"/volunteer");
	}

	# Eventually it won't be the rescue's responsibility to manage other volunteer accounts, enable, send account invite, reset passwords, etc.
	function _invite($id)  # Setting to 'Active' alone might give them  access if they have an account, but regardless we should explicitly contact them.
	{
		# Either "a user account has been created for you" or "you have been granted access to"

		$this->RescueVolunteer->id = $id;
		$volunteer = $this->RescueVolunteer->read();
		$email = $volunteer['Volunteer']['email'];
		
		$this->Rescue->recursive = -1;
		$this->Rescue->id = $volunteer['RescueVolunteer']['rescue_id'];
		$rescue = $this->Rescue->read();

		$vars = array(
			'rescue'=>$rescue,
			'volunteer'=>$volunteer
		);

		Configure::write("site_title", $rescue['Rescue']['title']);  # For emails...

		if($user_id = $volunteer['Volunteer']['user_id']) { # User already assigned (to profile).
			$user = $this->User->read(null,$user_id);

		} else if (($user = $this->User->findByEmail($email)) && !empty($user)) { # Already signed up.
			# that's it

		} else {  # Create  user account...
			if(!$this->User->save($volunteer['Volunteer'])) # Email, first_name, last_name
			{
				return $this->setError("Could not create user account for volunteer");
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

		# Assign that user_id to the volunteer account...
		$this->RescueVolunteer->saveField("user_id",$user_id);

		# Save profile too, so they can modify it, use it later, etc.
		$this->RescueVolunteer->Volunteer->id = $volunteer['Volunteer']['id'];
		$this->RescueVolunteer->Volunteer->saveField("user_id",$user_id);

		# **** how do we give them a url that'll go directly to the rescue once  they log in (ie if not dedicated url) ???????

		# pass  'redirect' field, that gets saved to Auth.redirect


		$this->sendUserEmail($user_id, "Volunteer access","rescue/volunteer_invited",$vars);

		$name = !empty($user['User']['first_name']) ? $user['User']['first_name'] : "The user";
		$email = $user['User']['email'];
		return $this->setSuccess("$name has been emailed instructions to $email for signing in",array('action'=>'index'));
	}

	function admin_edit($id=null)
	{
		$this->RescueVolunteer->autouser = false; # DOnt save as ours.
		$this->RescueVolunteer->Volunteer->autouser = false; # DOnt save as ours.

		if(!empty($this->request->data))
		{
			# Don't save to user account
			if($this->RescueVolunteer->saveAll($this->request->data))
			{
				if(!empty($this->request->data['RescueVolunteer']['invite']))
				{
					return $this->_invite($id);
				}
				$this->setSuccess("The volunteer application has been ".($id?"updated. ":"submitted. "), array('action'=>'index'));
			} else {
				$this->setError("Could not submit application. ".$this->Volunteer->errorString());

			}
		} 

		if(!empty($id))
		{
			$this->request->data = $this->RescueVolunteer->read(null, $id);
		}

		$this->set("volunteerForm", $this->VolunteerForm->singleton());
		$this->set("adoptables", $this->Adoptable->find('list',array('conditions'=>array('status'=>'Available'))));
		$this->set("statuses", $this->RescueVolunteer->dropdown('statuses'));
	}

	function edit()
	{
		# Don't save to user account if already signed in as a volunteer/rescuer with the rescue. ie doing for others.
		if($this->rescue_member())
		{
			$this->RescueVolunteer->autouser = false;
			$this->RescueVolunteer->Volunteer->autouser = false;
		}

		if(!empty($this->request->data))
		{
			# XXX even if they have a full volunteer application finished, we still should probably ask for 
			# "what you'd like to contribute/what you have to offer", etc for this specific instance.
			#
			if($this->RescueVolunteer->saveAll($this->request->data)) # Will save changes to volunteer profile as needed.#
			{
				# Maybe later send to someone else with a specific role.
				$this->sendVolunteerEmail($this->RescueVolunteer->read());
				$this->setSuccess("Your volunteer application has been submitted. Someone will contact you shortly.", array('action'=>'index'));
			} else {
				$this->setError("Could not submit application. ".$this->RescueVolunteer->errorString());

			}
		} 

		$this->set("volunteerForm", $this->VolunteerForm->first());
		$this->set("adoptables", $this->Adoptable->find('list',array('conditions'=>array('status'=>'Available'))));
		$this->set("statuses", $this->RescueVolunteer->statuses);
	}

	function admin_index()
	{
		$this->set("volunteers", $this->RescueVolunteer->find('all',array('conditions'=>array('status'=>'Active'))));
		$this->set("offlineVolunteers", $this->RescueVolunteer->find('all',array('conditions'=>array('status'=>'Active Offline'))));
		$this->set("applicants", $this->RescueVolunteer->find('all',array('conditions'=>array('status'=>'Applied'))));
		$this->set("inactives", $this->RescueVolunteer->find('all',array('conditions'=>array('status'=>'Inactive'))));
		$this->set("ignoreds", $this->RescueVolunteer->find('all',array('conditions'=>array('status'=>'Ignored'))));
	}

	function admin_status($id)
	{
		if(!empty($this->request->data))
		{
			if($this->RescueVolunteer->save($this->request->data))
			{
				if(!empty($this->request->data['RescueVolunteer']['invite']))
				{
					# XXX TODO
					return $this->_invite($id);
					#$this->setSuccess("Status updated and the user has been sent an email with instructions for signing in",array('action'=>'index')); 
				}  else  {
					$this->setSuccess("Status updated",array('action'=>'index')); 
				}
			} else {
				$this->setError("Could not update status: ".$this->RescueVolunteer->errorString(),array('action'=>'index'));
			}
		}
		$this->request->data = $this->RescueVolunteer->read(null,$id);
		$this->set("statuses", $this->RescueVolunteer->statuses);
	}

	function admin_view($id=null)
	{
		$this->set("rescueVolunteer", $this->RescueVolunteer->read(null,$id));
	}
}
