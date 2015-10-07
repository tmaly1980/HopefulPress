<?
App::uses("ListItemsController", "ListPage.Controller");
class AdoptionFaqsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'adoption_overviews','action'=>'view','#'=>'faq')); # What OUR "index" page should be.
	}

}
