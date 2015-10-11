<?
App::uses("DownloadsController", "Controller");
class VolunteerPageDownloadsController extends DownloadsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'volunteer_pages','action'=>'view','#'=>'downloads')); # What OUR "index" page should be.
	}
}
