<?
App::uses("SingletonController", "Singleton.Controller");
class EducationIndexesController extends SingletonController
{
	var $uses  = array('Rescue.EducationIndex','Rescue.EducationPage');

	function view($id=null)
	{
		$this->set("pages", $this->EducationPage->find('all'));
		$this->set("educationIndex", $this->EducationIndex->singleton());
	}

}
