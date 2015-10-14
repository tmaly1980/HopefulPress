<?
App::uses("PagesController", "Controller");
class FosterPagesController extends PagesController
{
	var $uses = array('FosterPage');
	function index()
	{
		return $this->redirect(array('controller'=>'foster_page_indices','action'=>'view','#'=>'pages'));
	}

	

}

