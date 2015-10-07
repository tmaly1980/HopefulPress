<?
App::uses("VideosController", "Videos.Controller");

class AdoptableVideosController extends VideosController
{
	var $uses = array('Rescue.AdoptableVideo');

	function user_edit($id=null)
	{
		if($this->_edit($id))
		{
			$adoptable_id = $this->AdoptableVideo->field("adoptable_id");
			$this->redirect(array('controller'=>'adoptables','action'=>'view',$adoptable_id));
		} # Named params (passed on add) should autofill!
		$this->set("parent_key", "adoptable_id");
	}
}
