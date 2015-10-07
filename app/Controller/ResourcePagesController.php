<?
App::uses("SingletonController", "Singleton.Controller");
class ResourcePagesController extends SingletonController
{
	var $uses = array('ResourcePage','Resource','ResourceCategory');

	function view()
	{
		$this->Tracker->track();
		$this->set("resourceCount", $this->Resource->findCount()); # Even those in cateogires, whether to show sort or not.
		$this->set("resources", $this->Resource->findAll(array('resource_category_id'=>null)));
		$this->set("resourceCategories", $this->ResourceCategory->findAll());
		return parent::view();
	}
}
