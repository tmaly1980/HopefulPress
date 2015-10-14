<?
App::uses("ListItemsController", "ListPage.Controller");
class DownloadsController extends ListItemsController
{
	function index()
	{
		$controller = Inflector::underscore(Inflector::singular($this->modelClass)."Pages");
		return $this->redirect(array('controller'=>$controller,'action'=>'view')); # What OUR "index" page should be.
	}

	function download($id = null)
	{
		#$this->track();
		if(!$this->{$this->modelClass}->echoFileContent($id))
		{
			return $this->invalid();
		}
	}

}
