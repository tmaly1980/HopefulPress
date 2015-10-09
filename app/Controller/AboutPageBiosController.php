<?
App::uses("ListItemsController", "ListPage.Controller");
class AboutPageBiosController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'rescues','action'=>'about','rescue'=>$this->rescuename)); # What OUR "index" page should be.
	}
}
