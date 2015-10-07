<?
App::uses("SingletonController", "Singleton.Controller");
class VolunteerOverviewsController extends SingletonController
{
	var $uses = array('Rescue.VolunteerOverview','Rescue.VolunteerFaq','Rescue.VolunteerDownload','Rescue.VolunteerPage','Rescue.VolunteerForm');

	function view($id=null)
	{
		$this->set("downloads", $this->VolunteerDownload->find('all'));
		$this->set("pages", $this->VolunteerPage->find('all'));
		$this->set("faqs", $this->VolunteerFaq->find('all'));
		$this->set("volunteerForm", $this->VolunteerForm->first());
		return parent::view($id);
	}

}
