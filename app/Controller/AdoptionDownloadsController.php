<?
App::uses("DownloadsController", "Controller");
class AdoptionDownloadsController extends DownloadsController
{
	function index()
	{
		return $this->redirect(array('controller'=>'adoption_page_indices','action'=>'view','#'=>'downloads')); # What OUR "index" page should be.
	}
}
