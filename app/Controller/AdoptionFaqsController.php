<?
App::uses("ListItemsController", "ListPage.Controller");
class AdoptionFaqsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'adoption_page_indices','action'=>'view','#'=>'faq')); # What OUR "index" page should be.
	}

}
