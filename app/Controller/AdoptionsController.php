<?
App::uses("RescueAppController", "Rescue.Controller");
class AdoptionsController extends RescueAppController
{
	var $uses  = array('Rescue.Adoption','Rescue.AdoptionForm','Rescue.Adoptable');

	function user_add()
	{
		if(!empty($this->request->data))
		{
			if($this->Adoption->save($this->request->data))
			{
				# Can also set 'status' field...
				#$this->sendAdoptionEmail($this->Adoption->read());
				# Don't bother sending when internally created.
				$this->setSuccess("The adoption application has been submitted. ", array('action'=>'index'));
			} else {
				$this->setError("Could not submit application. ".$this->Adoption->errorString());

			}
		} 

		$this->set("adoptionForm", $this->AdoptionForm->singleton());
		$this->set("adoptables", $this->Adoptable->available_list());
		$this->set("statuses", $this->Adoption->statuses);
	}

	function add()
	{
		if(!empty($this->request->data))
		{
			if($this->Adoption->save($this->request->data))
			{
				# Maybe later send to someone else with a specific role.
				$this->sendAdoptionEmail($this->Adoption->read());
				$this->setSuccess("Your adoption application has been submitted. Someone will contact you shortly.", "/adoption");
			} else {
				$this->setError("Could not submit application. ".$this->Adoption->errorString());

			}
		} 

		$this->set("adoptionForm", $this->AdoptionForm->singleton());
		$this->set("adoptables", $this->Adoptable->available_list());
	}

	function user_index()
	{
		$this->set("received", $this->Adoption->find('all',array('conditions'=>array('Adoption.status'=>'Received'))));
		$this->set("pending", $this->Adoption->find('all',array('conditions'=>array('Adoption.status'=>'Pending'))));
		$this->set("accepted", $this->Adoption->find('all',array('conditions'=>array('Adoption.status'=>'Accepted'))));
		$this->set("deferred", $this->Adoption->find('all',array('conditions'=>array('Adoption.status'=>'Deferred'))));
		$this->set("denied", $this->Adoption->find('all',array('conditions'=>array('Adoption.status'=>'Denied'))));
	}

	function user_view($id=null)
	{
		$this->set("adoption", $adoption=$this->Adoption->read(null,$id));

	}

	function user_status($id)
	{
		if(!empty($this->request->data))
		{
			if($this->Adoption->save($this->request->data))
			{
				$this->setSuccess("Status updated",array('user'=>1,'action'=>'view',$id)); 
			} else {
				$this->setError("Could not update status: ".$this->Adoption->errorString(),array('user'=>1,'action'=>'view',$id));
			}
		}
		$this->request->data = $this->Adoption->read(null,$id);
		$this->set("statuses", $this->Adoption->statuses);
	}
}
