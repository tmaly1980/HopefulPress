<?
App::uses("ListItemsController", "ListPage.Controller");
class ContactsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'contact_pages','action'=>'view')); # What OUR "index" page should be.
	}
}
