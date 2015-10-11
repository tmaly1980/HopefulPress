<?
App::uses("SingletonController", "Singleton.Controller");
class AdoptionOverviewsController extends SingletonController
{
	var $uses = array('Rescue.AdoptionOverview','Rescue.AdoptionFaq','Rescue.AdoptionDownload','Rescue.AdoptionPage');

	function ucThing() { return "Overview"; } # Generic for rescue AND sanctuary...

	function edit()
	{
		if($this->_edit())
		{
			$this->redirect($this->site("rescue_enabled")?"/adoption":"/sanctuary");
		}
	}

	function admin_disable_rescue()
	{
		$this->Site->id = $this->get_site_id();
		$this->Site->saveField("rescue_enabled",0);
		$this->setSuccess("You've switched your website to operate as a long-term care sanctuary. All information regarding adoptions and fostering are now removed.","/sanctuary");
	}

	function admin_enable_rescue()
	{
		$this->Site->id = $this->get_site_id();
		$this->Site->saveField("rescue_enabled",1);
		$this->setSuccess("You've switched your website to operate as a short-term foster-based rescue. Adoption and foster forms and features are now available.","/adoption");
	}


	function view($id=null)
	{
		$this->set("downloads", $this->AdoptionDownload->find('all'));
		$this->set("pages", $this->AdoptionPage->find('all'));
		$this->set("faqs", $this->AdoptionFaq->find('all'));
		return parent::view($id);
	}

}
