<?
App::uses("ListItemsController", "ListPage.Controller");
class AboutPageBiosController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'about_pages','action'=>'view')); # What OUR "index" page should be.
	}
}
