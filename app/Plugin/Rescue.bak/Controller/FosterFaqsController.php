<?
App::uses("ListItemsController", "ListPage.Controller");
class FosterFaqsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'foster_overviews','action'=>'view','#'=>'faq')); # What OUR "index" page should be.
	}

}
