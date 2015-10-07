<?

class MemberPagesController extends AppController
{
	var $uses = array('Members.MemberPage','NewsPost','Page','Event','PhotoAlbum','Link','Download');#,'Video','AudioClip');

	#############################
	function admin_enable()
	{
		$this->MemberPage->id = $this->MemberPage->first_id();
		$this->MemberPage->saveField("enabled",1);
		$this->setSuccess("Members area has been enabled", array('members'=>1,'action'=>'view'));
	}

	function admin_disable()
	{
		$this->MemberPage->id = $this->MemberPage->first_id();
		$this->MemberPage->saveField("enabled",0);
		$this->setSuccess("Members area has been disabled", array('members'=>1,'action'=>'view'));
	}

	public function admin_edit($id = null) {
		$thing = $this->thing();
		if (!empty($this->request->data)) { 
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->setSuccess("The $thing has been saved", array('members'=>1,'action'=>'view',$this->{$this->modelClass}->id));
			} else {
				$this->setError("The $thing could not be saved: ". $this->{$this->modelClass}->errorString());
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}
		Configure::write("members_only", true);
	}

	function members_view()
	{
		if($memberPage = $this->MemberPage->first(array("enabled"=>1)))
		{
			$this->set("memberPage", $memberPage);
			$this->set("updates", $this->updates());
		}
	}

	function view() { $this->redirect(array('members'=>1,'action'=>'view')); } # LOGIN!

	public function members_edit($id = null) {
		return $this->_edit($id);
	}

	#############################

	function members_index() { $this->redirect(array('action'=>'view')); } # Singleton

	function updates()
	{
		# Sectionable will get proper context.

		return array(
			'pages'=>$this->Page->findAll(array('Page.parent_id'=>null,10=>10)),
			'newsPosts'=>$this->NewsPost->find('recent'),
			'upcomingEvents'=>$this->Event->findAll(array('future'=>1)),
			'recentEvents'=>$this->Event->findAll(array('future'=>0)),
			'photoAlbums'=>$this->PhotoAlbum->find('recent'),
			'links'=>$this->Link->find('all'),
			'downloads'=>$this->Download->find('all'),
		);
	}

}


