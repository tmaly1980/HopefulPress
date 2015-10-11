<?
App::uses("ListItemsController", "ListPage.Controller");
class VolunteerPageFaqsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'volunteer_pages','action'=>'view','#'=>'faq')); # What OUR "index" page should be.
	}

}
