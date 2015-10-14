<?
App::uses("DownloadsController", "Controller");
class FosterDownloadsController extends DownloadsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'foster_page_indices','action'=>'view','#'=>'downloads')); # What OUR "index" page should be.
	}
}
