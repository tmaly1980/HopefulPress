<?
App::uses("DownloadsController", "Controller");
class AdoptionDownloadsController extends DownloadsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'adoption_overviews','action'=>'view','#'=>'downloads')); # What OUR "index" page should be.
	}
}
