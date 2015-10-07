<?
App::uses("ListItemsController", "ListPage.Controller");
class ResourcesController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'resource_pages','action'=>'view','project_id'=>$this->pid())); # What OUR "index" page should be.
	}

	function edit($id=null)
	{
		$this->set("resourceCategories", $this->Resource->ResourceCategory->find('list'));
		parent::edit($id);
	}

	function follow()
	{
		#$this->Tracker->track(); # TODO someday
		if(empty($this->request->query['goto']))
		{
			return $this->redirect($this->request->query['goto']);
		}
		$this->setFlash("Sorry, could not follow resource", array('action'=>'index'));
	}
}
