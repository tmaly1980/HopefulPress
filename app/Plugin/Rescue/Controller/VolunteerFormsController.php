<?
App::uses("SingletonController", "Singleton.Controller");
class VolunteerFormsController extends SingletonController
{
	function index()
	{
		$this->redirect(array('controller'=>'volunteer_overviews','action'=>'index'));
	}

	function view()
	{
		$this->redirect(array('controller'=>'volunteers','action'=>'add'));
	}

	function user_edit()
	{
		return $this->edit();
	}

}
