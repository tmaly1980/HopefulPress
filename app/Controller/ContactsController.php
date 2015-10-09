<?
App::uses("ListItemsController", "ListPage.Controller");
class ContactsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'rescues','action'=>'contact','rescue'=>$this->rescuename)); # What OUR "index" page should be.
	}
}
