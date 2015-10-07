<?
App::uses("ListItemsController", "ListPage.Controller");
class VolunteerFaqsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'volunteer_overviews','action'=>'view','#'=>'faq')); # What OUR "index" page should be.
	}

}
