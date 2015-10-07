<?
App::uses("ListItemsController", "ListPage.Controller");
class LinksController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'link_pages','action'=>'view','project_id'=>$this->pid())); # What OUR "index" page should be.
	}

	function edit($id=null)
	{
		$this->set("linkCategories", $this->Link->LinkCategory->find('list'));
		parent::edit($id);
	}

	function follow()
	{
		#$this->Tracker->track(); # TODO someday
		if(empty($this->request->query['goto']))
		{
			return $this->redirect($this->request->query['goto']);
		}
		$this->setFlash("Sorry, could not follow link", array('action'=>'index'));
	}
}
