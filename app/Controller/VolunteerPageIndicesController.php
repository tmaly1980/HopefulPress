<?
App::uses("SingletonController", "Singleton.Controller");
class VolunteerPageIndicesController extends SingletonController
{
	var $uses = array('VolunteerPageIndex','Volunteer','VolunteerFaq','VolunteerDownload','VolunteerPage','VolunteerForm','RescueVolunteer');

	function view($id=null)
	{
		$this->set("statuses", $this->RescueVolunteer->statuses);
		$this->set("downloads", $this->VolunteerDownload->find('all'));
		$this->set("pages", $this->VolunteerPage->find('all'));
		$this->set("faqs", $this->VolunteerFaq->find('all'));
		$this->set("volunteerForm", $this->VolunteerForm->singleton()); # Create if needed. Unless disabled.

		# Try to pre-load form if can.
		if($user_id = $this->me())
		{
			$this->request->data = $this->Volunteer->findByUserId($user_id);
		}
		return parent::view($id);
	}

}
