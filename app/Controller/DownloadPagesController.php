<?
App::uses("SingletonController", "Singleton.Controller");
class DownloadPagesController extends SingletonController
{
	var $uses = array('DownloadPage','Download');

	function view()
	{
		$this->Tracker->track();
		$this->set("downloads", $this->Download->findAll());
		return parent::view();
	}
}
