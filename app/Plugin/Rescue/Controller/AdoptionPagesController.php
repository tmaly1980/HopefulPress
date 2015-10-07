<?
App::uses("PagesController", "Controller");
class AdoptionPagesController extends PagesController
{
	var $uses = array('Rescue.AdoptionPage');
	function index()
	{
		return $this->redirect(array('controller'=>'adoption_overviews','action'=>'view','#'=>'pages'));
	}

	

}
