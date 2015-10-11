<?
App::uses("SingletonController", "Singleton.Controller");
class VolunteerPageFormsController extends SingletonController
{
	function index()
	{
		$this->redirect(array('controller'=>'volunteer_page_indices','action'=>'index'));
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
