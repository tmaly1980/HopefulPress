<?
App::uses("DownloadsController", "Controller");
class VolunteerDownloadsController extends DownloadsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'volunteer_page_indices','action'=>'view','#'=>'downloads')); # What OUR "index" page should be.
	}
}
