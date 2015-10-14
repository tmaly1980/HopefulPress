<?
App::uses("SingletonController", "Singleton.Controller");
class VolunteerFormsController extends SingletonController
{
	function index()
	{
		$this->redirect(array('controller'=>'volunteer_page_indices','action'=>'view'));#,'#'=>'volunteerForm'));
	}

	function view()
	{
		$this->redirect(array('controller'=>'volunteer_page_indices','action'=>'view'));#,'#'=>'volunteerForm'));
	}

	function user_edit()
	{
		return $this->edit();
	}

}
