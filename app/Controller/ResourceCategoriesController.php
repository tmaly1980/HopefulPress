<?
App::uses("ListCategoriesController", "ListPage.Controller");
class ResourceCategoriesController extends ListCategoriesController
{
	function index()
	{
		$this->redirect(array('controller'=>'resource_pages','action'=>'view')); # Post-delete redirect
	}
}
