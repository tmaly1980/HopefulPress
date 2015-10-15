<?
App::uses("SingletonController", "Singleton.Controller");
class AdoptionFormsController extends SingletonController
{
	function index()
	{
		$this->redirect(array('controller'=>'adoption_page_indices','action'=>'index'));
	}

	function view()
	{
		$this->redirect(array('controller'=>'adopters','action'=>'add'));
	}

	function user_edit()
	{
		return $this->edit();
	}

}
