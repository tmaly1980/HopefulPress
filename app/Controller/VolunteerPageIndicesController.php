<?
App::uses("SingletonController", "Singleton.Controller");
class VolunteerPageIndicesController extends SingletonController
{
	var $uses = array('VolunteerPageIndex','VolunteerPageFaq','VolunteerPageDownload','VolunteerPage','VolunteerForm');

	function view($id=null)
	{
		$this->set("downloads", $this->VolunteerPageDownload->find('all'));
		$this->set("pages", $this->VolunteerPage->find('all'));
		$this->set("faqs", $this->VolunteerPageFaq->find('all'));
		$this->set("volunteerForm", $this->VolunteerPageForm->first());
		return parent::view($id);
	}

}
