<?
App::uses('ProjectCoreController', 'Project.Controller');

class ProjectsController extends ProjectCoreController
{
	var $uses = array('Project.Project','Project.ProjectUser','NewsPost','Page','Event','PhotoAlbum','Link','Download');#,'Video','AudioClip');

	function view($id = null)
	{
		$this->track();
		return parent::view($id);
	}

	function index()
	{
		$this->track();
		return parent::index();
	}

	function updates()
	{
		# Projectable will get proper context.

		return array(
			'pages'=>$this->Page->findAll(array('Page.parent_id'=>null)),
			'newsPosts'=>$this->NewsPost->find('recent'),
			'upcomingEvents'=>$this->Event->findAll(array('future'=>1)),
			'recentEvents'=>$this->Event->findAll(array('future'=>0)),
			'photoAlbums'=>$this->PhotoAlbum->find('recent'),
			'links'=>$this->Link->find('all'),
			'downloads'=>$this->Download->find('all'),
			#'videos'=>$this->Video->find('recent'),
			#'audioClips'=>$this->AudioClip->find('recent')
		);
	}


}

