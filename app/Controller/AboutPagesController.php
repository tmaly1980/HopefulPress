<?
App::uses("SingletonController", "Singleton.Controller");
class AboutPagesController extends SingletonController
{
	var $uses = array('AboutPage','AboutPageBio');

	var $autocreate = false;

	function view()
	{
		$this->Tracker->track();
		$this->set("aboutPageBios", $this->AboutPageBio->findAll());
		return parent::view();
	}
}
