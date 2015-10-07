<?
App::uses("SingletonController", "Singleton.Controller");
class LinkPagesController extends SingletonController
{
	var $uses = array('LinkPage','Link','LinkCategory');

	function view()
	{
		$this->Tracker->track();
		$this->set("linkCount", $this->Link->findCount()); # Even those in cateogires, whether to show sort or not.
		$this->set("links", $this->Link->findAll(array('link_category_id'=>null)));
		$this->set("linkCategories", $this->LinkCategory->findAll());
		return parent::view();
	}
}
