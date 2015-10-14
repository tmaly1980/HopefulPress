<?
App::uses("ListItemsController", "ListPage.Controller");
class VolunteerFaqsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'volunteer_page_indices','action'=>'view','#'=>'faq')); # What OUR "index" page should be.
	}

}
