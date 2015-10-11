<?
App::uses("SingletonController", "Singleton.Controller");
class AdoptionFormsController extends SingletonController
{
	function index()
	{
		$this->redirect(array('controller'=>'adoption_overviews','action'=>'index'));
	}

	function view()
	{
		$this->redirect(array('controller'=>'adoptions','action'=>'add'));
	}

	function user_edit()
	{
		return $this->edit();
	}

}
