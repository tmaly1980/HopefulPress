<?
#App::uses("RescueAppController", "Rescue.Controller");
#class FostersController extends RescueAppController
App::uses('UsersController', 'Controller');
class FostersController extends UsersController # Easy inherit.
{
	var $uses  = array('Rescue.Foster','Rescue.FosterForm','Rescue.Adoptable');

	function admin_edit($id=null)
	{
		if(!empty($this->request->data))
		{
			if($this->Foster->save($this->request->data))
			{
				# Can also set 'status' field...
				if(!empty($this->request->data['Foster']['invite']))
				{
					return $this->_send_invite($this->Foster->id);
				}
				#$this->sendFosterEmail($this->Foster->read());
				# Don't bother sending when internally created.
				$this->setSuccess("The foster application has been submitted. ", array('action'=>'index'));
			} else {
				$this->setError("Could not submit application. ".$this->Foster->errorString());

			}
		} 

		if(!empty($id))
		{
			$this->request->data = $this->Foster->read(null,$id);
		}

		$this->set("fosterForm", $this->FosterForm->singleton());
		$this->set("adoptables", $this->Adoptable->find('list',array('conditions'=>array('status'=>'Available'))));
		$this->set("statuses", $this->Foster->statuses);
	}

	function edit()
	{
		if(!empty($this->request->data))
		{
			if($this->Foster->save($this->request->data))
			{
				# Maybe later send to someone else with a specific role.
				$this->sendFosterEmail($this->Foster->read());
				$this->setSuccess("Your foster application has been submitted. Someone will contact you shortly.", "/foster");
			} else {
				$this->setError("Could not submit application. ".$this->Foster->errorString());

			}
		} 

		$this->set("fosterForm", $this->FosterForm->singleton());
		$this->set("adoptables", $this->Adoptable->find('list',array('conditions'=>array('status'=>'Available'))));
		$this->set("statuses", $this->Foster->statuses);
	}

	function admin_index()
	{
		$this->set("fosters", $this->Foster->find('all',array('conditions'=>array('status'=>'Active'))));
		$this->set("applicants", $this->Foster->find('all',array('conditions'=>array('status'=>'Applied'))));
		$this->set("rejecteds", $this->Foster->find('all',array('conditions'=>array('status'=>'Rejected'))));
		$this->set("inactives", $this->Foster->find('all',array('conditions'=>array('status'=>'Inactive'))));
	}

	function admin_status($id)
	{
		if(!empty($this->request->data))
		{
			if($this->Foster->save($this->request->data))
			{
				if(!empty($this->request->data['Foster']['invite']))
				{
					return $this->_send_invite($id);
					#$this->setSuccess("Status updated and the user has been sent an email with instructions for signing in",array('action'=>'index')); 
				}  else  {
					$this->setSuccess("Status updated",array('action'=>'index')); 
				}
			} else {
				$this->setError("Could not update status: ".$this->Foster->errorString(),array('action'=>'index'));
			}
		}
		$this->request->data = $this->Foster->read(null,$id);
		$this->set("statuses", $this->Foster->statuses);
	}

	function admin_view($id=null)
	{
		$this->set("foster", $this->Foster->read(null,$id));
	}
}
