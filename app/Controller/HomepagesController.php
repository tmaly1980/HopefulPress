<?
App::uses("SingletonController", "Singleton.Controller");
class HomepagesController extends SingletonController
{
	var $uses = array('Homepage','Page','NewsPost','Event','PhotoAlbum','Videos.Video','Rescue.RescueHomepage');

	function admin_preview($id = null)
	{
		# Used in site_design preview,so cant do much.
		$this->view();
	}

	function view()
	{
		if($this->site("hostname") == 'demo')
		{
			$this->track('Marketing');
		}
		$this->track();
		error_log("IN HOME");
		$updates = array(
			'newsPosts'=>$this->NewsPost->find('recent'),
			'photoAlbums'=>$this->PhotoAlbum->find('recent'),
			'upcomingEvents'=>$this->Event->find('upcoming'),
			'previousEvents'=>$this->Event->find('previous'),
			'videos'=>$this->Video->find('recent'),

		);
		$this->set("topics", $this->Page->findAll(array('Page.parent_id'=>null)));
		$this->set("updates", $updates);

		$this->set("rescueHomepage", $this->RescueHomepage->singleton());
		return parent::view();
	}

	function admin_edit()
	{
		/* this kills the site design unless we fix with ID first.
		if(!empty($this->request->data['SiteDesign']))
		{
			if(!$this->SiteDesign->save($this->request->data))
			{
				$this->setError("Could not save settings: ".$this->SiteDesign->errorString());
			}
		}
		*/
		# Rescue homepage stuff is all inline edits, no need here.
		if($this->_edit())
		{
			$this->redirect("/homepages/view");
		}

		$siteDesign = $this->SiteDesign->singleton();
		$this->set("rescueHomepage", $rescueHomepage = $this->RescueHomepage->singleton()); # Needed for preview.
		$this->request->data = array_merge($this->request->data, $siteDesign);
	}
}
