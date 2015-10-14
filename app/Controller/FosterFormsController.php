<?
App::uses("SingletonController", "Singleton.Controller");
class FosterFormsController extends SingletonController
{
	function index()
	{
		$this->redirect(array('controller'=>'foster_page_indices','action'=>'view'));#,'#'=>'fosterForm'));
	}

	function view()
	{
		$this->redirect(array('controller'=>'foster_page_indices','action'=>'view'));#,'#'=>'fosterForm'));
	}

	function user_edit()
	{
		return $this->edit();
	}

}
