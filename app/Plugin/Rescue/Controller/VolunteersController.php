<?
#App::uses("RescueAppController", "Rescue.Controller");
#class VolunteersController extends RescueAppController
App::uses('UsersController', 'Controller');
class VolunteersController extends UsersController # Easy inherit.
{
	var $uses  = array('Rescue.Volunteer','Rescue.VolunteerForm','Rescue.Adoptable');

	function index()
	{
		$this->redirect("/volunteer");
	}

	function admin_edit($id=null)
	{
		if(!empty($this->request->data))
		{
			if($this->Volunteer->save($this->request->data))
			{
				# Can also set 'status' field...
				if(!empty($this->request->data['Volunteer']['invite']))
				{
					return $this->_send_invite($this->Volunteer->id);
				}
				#$this->sendVolunteerEmail($this->Volunteer->read());
				# Don't bother sending when internally created.
				$this->setSuccess("The volunteer application has been submitted. ", array('action'=>'index'));
			} else {
				$this->setError("Could not submit application. ".$this->Volunteer->errorString());

			}
		} 

		if(!empty($id))
		{
			$this->request->data = $this->Volunteer->read(null, $id);
		}

		$this->set("volunteerForm", $this->VolunteerForm->singleton());
		$this->set("adoptables", $this->Adoptable->find('list',array('conditions'=>array('status'=>'Available'))));
		$this->set("statuses", $this->Volunteer->statuses);
	}

	function edit()
	{
		if(!empty($this->request->data))
		{
			if($this->Volunteer->save($this->request->data))
			{
				# Maybe later send to someone else with a specific role.
				$this->sendVolunteerEmail($this->Volunteer->read());
				$this->setSuccess("Your volunteer application has been submitted. Someone will contact you shortly.", "/volunteer");
			} else {
				$this->setError("Could not submit application. ".$this->Volunteer->errorString());

			}
		} 

		$this->set("volunteerForm", $this->VolunteerForm->first());
		$this->set("adoptables", $this->Adoptable->find('list',array('conditions'=>array('status'=>'Available'))));
		$this->set("statuses", $this->Volunteer->statuses);
	}

	function admin_index()
	{
		$this->set("volunteers", $this->Volunteer->find('all',array('conditions'=>array('status'=>'Active'))));
		$this->set("applicants", $this->Volunteer->find('all',array('conditions'=>array('status'=>'Applied'))));
		$this->set("rejecteds", $this->Volunteer->find('all',array('conditions'=>array('status'=>'Rejected'))));
		$this->set("inactives", $this->Volunteer->find('all',array('conditions'=>array('status'=>'Inactive'))));
	}

	function admin_status($id)
	{
		if(!empty($this->request->data))
		{
			if($this->Volunteer->save($this->request->data))
			{
				if(!empty($this->request->data['Volunteer']['invite']))
				{
					return $this->_send_invite($id);
					#$this->setSuccess("Status updated and the user has been sent an email with instructions for signing in",array('action'=>'index')); 
				}  else  {
					$this->setSuccess("Status updated",array('action'=>'index')); 
				}
			} else {
				$this->setError("Could not update status: ".$this->Volunteer->errorString(),array('action'=>'index'));
			}
		}
		$this->request->data = $this->Volunteer->read(null,$id);
		$this->set("statuses", $this->Volunteer->statuses);
	}

	function admin_view($id=null)
	{
		$this->set("volunteer", $this->Volunteer->read(null,$id));
	}
}
