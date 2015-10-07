<?
App::uses("ListItemsController", "ListPage.Controller");
class IntakeSurveyFilesController extends ListItemsController
{
	var $uses = array('Www.IntakeSurveyFile');

	function download($id = null)
	{
		#$this->Tracker->track();
		if(!$this->IntakeSurveyFile->echoFileContent($id))
		{
			return $this->invalid();
		}
	}

}
