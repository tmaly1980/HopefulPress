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

	function admin_edit($id=null)
	{
		if(!empty($this->request->data))
		{
			if($this->RescueVolunteer->saveAll($this->request->data))
			{
				/* ?????
				# Can also set 'status' field...
				if(!empty($this->request->data['RescueVolunteer']['invite']))
				{
					return $this->_send_invite($this->Volunteer->id);
				}
				#$this->sendVolunteerEmail($this->Volunteer->read());
				# Don't bother sending when internally created.
				*/
				$this->setSuccess("The volunteer application has been submitted. ", array('action'=>'index'));
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
		$this->set("statuses", $this->RescueVolunteer->statuses);
	}

	function edit()
	{
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
		$this->set("applicants", $this->RescueVolunteer->find('all',array('conditions'=>array('status'=>'Applied'))));
		$this->set("rejecteds", $this->RescueVolunteer->find('all',array('conditions'=>array('status'=>'Rejected'))));
		$this->set("inactives", $this->RescueVolunteer->find('all',array('conditions'=>array('status'=>'Inactive'))));
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
					return $this->_send_invite($id);
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
		$this->set("volunteer", $this->RescueVolunteer->read(null,$id));
	}
}
