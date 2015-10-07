<?
App::uses("SingletonController", "Singleton.Controller");
class ContactPagesController extends SingletonController
{
	var $uses = array('ContactPage','Contact');

	var $helpers = array('Core.GoogleMap');

	var $autocreate = false;

	function view()
	{
		$this->Tracker->track();
		$this->set("contacts", $this->Contact->findAll());
		return parent::view();
	}
}
