<?
App::uses("ForumAppController", "Forum.Controller");
class DiscussionsController extends ForumAppController
{
	var $uses = array('Forum.Discussion');

	function index()
	{
		$this->Discussion->recursive = 2;
		$this->set("discussions", $this->Discussion->find('all'));
	}

	function view($id=null)
	{
		if(empty($id)) { $this->redirect(array('action'=>'index')); }
		$this->Discussion->recursive = 2;
		$this->set("discussion", $discussion = $this->Discussion->read(null, $id));
		# Clear notified flag.
		if($this->Auth->me())
		{
			$this->Discussion->Message->saveAll(array('notified'=>0),array('discussion_id'=>$id,'user_id'=>$this->Auth->me()));
		}
	}

	function manager_index() { return $this->redirect(array('manager'=>null,'action'=>'index')); }
	function manager_view($id) { return $this->redirect(array('manager'=>null,'action'=>'view',$id)); }

	function manager_edit($id=null)
	{
		if(!empty($this->request->data))
		{
			if($this->Discussion->saveAll($this->request->data))
			{
				if($id)
				{
					return $this->setSuccess("Discussion updated", array('action'=>'view',$id));
				} else  {
					return $this->setSuccess("Discussion added", array('action'=>'index'));
				}
			} else {
				return $this->setError("Could not save discussion");
			}
		} else  if (!empty($id)) { 
			$this->request->data = $this->Discussion->read(null,$id);
		}
	}

	function manager_delete($id)
	{
		if(empty($id)) { return $this->setError("No discussion specified for deletion",array('controller'=>'discussions','action'=>'index')); }

		if($this->Discussion->delete($id))
		{
			return $this->setSuccess("Discussion deleted", array('controller'=>'discussions','action'=>'index'));
		} else {
			return $this->setError("Could not delete discussion", array('controller'=>'discussions','action'=>'view',$did));
		}
	}
}
