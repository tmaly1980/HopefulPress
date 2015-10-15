<?
App::uses("PagesController", "Controller");
class AdoptionPagesController extends PagesController
{
	var $uses = array('AdoptionPage');
	function index()
	{
		return $this->redirect(array('controller'=>'adoption_page_indices','action'=>'view','#'=>'pages'));
	}

	

}
