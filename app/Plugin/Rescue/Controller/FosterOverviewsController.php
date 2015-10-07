<?
App::uses("SingletonController", "Singleton.Controller");
class FosterOverviewsController extends SingletonController
{
	var $uses = array('Rescue.FosterOverview','Rescue.FosterFaq','Rescue.FosterDownload','Rescue.FosterPage','Rescue.FosterForm');

	var $autocreate = false; # DONT create if doens't exist, it's  a page we need to be EXPLICIT to enable...

	function view($id=null)
	{
		$this->set("downloads", $this->FosterDownload->find('all'));
		$this->set("pages", $this->FosterPage->find('all'));
		$this->set("faqs", $this->FosterFaq->find('all'));
		$this->set("fosterForm", $this->FosterForm->find('first'));
		$this->set("adoptables", $this->Adoptable->available_list());
		$this->set("fosterOverview", $this->FosterOverview->first(array('disabled'=>0)));
	}

}
