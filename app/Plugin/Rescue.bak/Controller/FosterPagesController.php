<?
App::uses("PagesController", "Controller");
class FosterPagesController extends PagesController
{
	var $uses = array('Rescue.FosterPage');
	function index()
	{
		return $this->redirect(array('controller'=>'foster_overviews','action'=>'view','#'=>'pages'));
	}

	

}
