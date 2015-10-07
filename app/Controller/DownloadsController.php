<?
App::uses("ListItemsController", "ListPage.Controller");
class DownloadsController extends ListItemsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'download_pages','action'=>'view','project_id'=>$this->pid())); # What OUR "index" page should be.
	}

	function download($id = null)
	{
		#$this->Tracker->track();
		if(!$this->Download->echoFileContent($id))
		{
			return $this->invalid();
		}
	}

}
