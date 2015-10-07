<?
App::uses("PagesController", "Controller");
class VolunteerPagesController extends PagesController
{
	var $uses = array('Rescue.VolunteerPage');
	function index()
	{
		return $this->redirect(array('controller'=>'volunteer_overviews','action'=>'view','#'=>'pages'));
	}

	

}
