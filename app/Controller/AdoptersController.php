<?
class AdoptersController extends AppController
{
	var $uses  = array('Adopter','AdoptionForm','Adoptable');

	function user_add()
	{
		$this->Adopter->autouser = false; # DOnt save as ours.

		$this->add();
	}

	function index()
	{
		return $this->redirect(array('controller'=>'adoption_page_indices','action'=>'view'));
	}

	function add()
	{
		if(!empty($this->request->data))
		{
			if($this->Adopter->saveAll($this->request->data))
			{
				# Maybe later send to someone else with a specific role.
				$this->sendAdoptionEmail($this->Adopter->read());
				$this->setSuccess("Your adoption application has been submitted. Someone will contact you shortly.", array('action'=>'index'));
			} else {
				$this->setError("Could not submit application. ".$this->Adopter->errorString());

			}
		} 

		if(!empty($this->request->named['adoptable_id']))
		{
			$this->Adoptable->id = $this->request->named['adoptable_id'];
			$this->set("adoptable", $this->Adoptable->field('name'));
		}


		$this->set("adoptionForm", $this->AdoptionForm->singleton());
		$this->set("adoptables", $this->Adoptable->available_list());
		$this->set("statuses", $this->Adopter->dropdown('statuses'));
	}

	function user_index()
	{
		$this->set("received", $this->Adopter->find('all',array('conditions'=>array('Adopter.status'=>'Received'))));
		$this->set("pending", $this->Adopter->find('all',array('conditions'=>array('Adopter.status'=>'Pending'))));
		$this->set("approved", $this->Adopter->find('all',array('conditions'=>array('Adopter.status'=>'Approved'))));
		$this->set("deferred", $this->Adopter->find('all',array('conditions'=>array('Adopter.status'=>'Deferred'))));
		$this->set("denied", $this->Adopter->find('all',array('conditions'=>array('Adopter.status'=>'Denied'))));
	}

	function user_view($id=null)
	{
		$this->set("adopter", $this->Adopter->read(null,$id));

	}

	function user_status($id)
	{
		if(!empty($this->request->data))
		{
			# ALLOW assigning an adoptable  too!
			if(!empty($this->request->data['Adoptable']['id']))
			{
				if($this->request->data['Adopter']['status'] == 'Approved') # save adoptable status too.
				{
					$this->request->data['Adoptable']['adopter_id'] = $id;
					$this->request->data['Adoptable']['status'] = 'Adopted';
					$this->request->data['Adoptable']['date_adopted'] = date('Y-m-d'); # Why else change this stuff?
				} else if($this->request->data['Adopter']['status'] == 'Pending') # save adoptable status too.
				{
					$this->request->data['Adoptable']['adopter_id'] = $id;
					$this->request->data['Adoptable']['status'] = 'Pending Adoption';
				}
			} else {
				unset($this->request->data['Adoptable']);
			}
			if($this->Adopter->saveAll($this->request->data))
			{
				$this->setSuccess("Status updated",array('user'=>1,'action'=>'view',$id)); 
			} else {
				$this->setError("Could not update status: ".$this->Adopter->errorString(),array('user'=>1,'action'=>'view',$id));
			}
		}
		$this->request->data = $this->Adopter->read(null,$id);
		$this->set("statuses", $this->Adopter->dropdown('statuses'));
		$this->set("adoptables", $this->Adoptable->available_list());
	}
}
