<?
App::uses("SingletonController", "Singleton.Controller");
class FosterFormsController extends SingletonController
{
	function index()
	{
		$this->redirect(array('controller'=>'foster_overviews','action'=>'index'));
	}

	function view()
	{
		$this->redirect(array('controller'=>'fosters','action'=>'add'));
	}

	function user_edit()
	{
		return $this->edit();
	}

}
