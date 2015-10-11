<?
App::uses("PagesController", "Controller");
class VolunteerPagesController extends PagesController
{
	function index()
	{
		return $this->redirect(array('controller'=>'volunteer_page_indices','action'=>'view','#'=>'pages'));
	}

	

}
