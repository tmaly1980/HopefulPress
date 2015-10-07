<?
App::uses("ListCategoriesController", "ListPage.Controller");
class LinkCategoriesController extends ListCategoriesController
{
	function index()
	{
		$this->redirect(array('controller'=>'link_pages','action'=>'view')); # Post-delete redirect
	}
}
