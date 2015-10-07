<?
class PortalController extends AppController
{
	var $uses = array();

	function index()
	{
		Configure::load("Rescue.breeds");
		$breeds = Configure::read("Breeds");
		$this->set("breeds", $breeds);
		$species = array_combine(array_keys($breeds), array_keys($breeds));
		$this->set("species", $species);

	}

}
