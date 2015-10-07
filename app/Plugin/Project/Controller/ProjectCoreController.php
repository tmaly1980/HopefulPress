<?
App::uses('ProjectCoreController', 'Project.Controller');

class ProjectCoreController extends AppController
{

	#############################
	public function index() {
		#$this->Tracker->track();
		$this->set($this->{$this->modelClass}->thingVars(), $this->{$this->modelClass}->find('all'));
	}

	public function view($id = null) {
		#$this->Tracker->track();
		if (!$id || !$this->{$this->modelClass}->count($id)) { return $this->invalid(); }
		$this->{$this->modelClass}->recursive = 2;
		$section = $this->{$this->modelClass}->read(null, $id);
		$this->set($this->thingVar(), $section);

		$idVar = Inflector::underscore($this->modelClass)."_id";
		# project_id, member_page_id

		Configure::write($idVar, $section[$this->modelClass]['id']); # Since we could be working with URL's
		Configure::write($this->thingVar(), $section);

		# Get subcontent
		$updates = $this->updates();
		$this->set("updates", $updates);
	}

	public function admin_edit($id = null) {
		$thing = $this->thing();
		if (!empty($this->request->data)) { 
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->setSuccess("The $thing has been saved", array('action'=>'view',$this->{$this->modelClass}->id));
			} else {
				$this->setError("The $thing could not be saved: ". $this->{$this->modelClass}->errorString());
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}
	}

	function admin_owner($pid = null) # Really only applies to projects... (singletons cant have owners)
	{ 
		if(!empty($this->request->data['Project']['user_id']))
		{
			$this->Project->id = $pid;
			$user_id = $this->request->data['Project']['user_id'];
			if($this->Project->saveField("user_id", $user_id))
			{
				$project = $this->Project->read();
				$vars = array('project'=>$project);
				$this->sendEmail($user_id, "You have been assigned ownership to a project", "users/project_owner", $vars);
				$this->setSuccess("Project owner changed. The user has been notified via email at $email.",array('action'=>'view','project_id'=>$pid));
			} else {
				$this->setError("Could not switch project owner: ". $this->Project->errorString());
				return false;
			}
		}
		$this->set("project", $this->Project->read(null, $pid));
		$this->set("users", $this->User->find('list'));
	}

	# Only projects (not members areas) have specific/exclusive (content write) access
	#
	function admin_users($pid = null)
	{
		$users = $this->Project->ProjectUser->findAll();
		$this->set("projectUsers", $users);
		$this->set("users", $this->User->find('list'));
	}

	function admin_user_add($pid)
	{
		if(!empty($this->request->data))
		{
			if($this->Project->ProjectUser->save($this->request->data))
			{
				$user_id = $this->request->data['ProjectUser']['user_id'];
				$project = $this->Project->read(null, $pid);
				$projectUser = $this->Project->ProjectUser->read();
				$vars = array(
					'project'=>$project,
					'projectUser'=>$projectUser,
				);
				$this->sendEmail($user_id, "You have been added to a project", "users/project", $vars);
				$this->setSuccess("The user has been added to the project.", array('action'=>'users','project_id'=>$pid));
			} else {
				return $this->setError("Could not add user to project: ". $this->ProjectUser->errorString());
			}
		}
		$existingUIDs = $this->Project->ProjectUser->field('user_id',array('project_id'=>$pid));
		# Exclude people already on list.
		$users = $this->User->find('list',array('conditions'=>array('id NOT'=>$existingUIDs)));

		$this->set("pid", $pid);
		$this->set("users", $users);
	}

	function admin_user_delete($pid, $id)
	{
		if($this->Project->ProjectUser->deleteAll(array('ProjectUser.project_id'=>$pid,'ProjectUser.user_id'=>$id)))
		{
			$this->setSuccess("The user has been removed from the project.", array('action'=>'users','project_id'=>$pid));
		} else {
			$this->setError("The user could not be removed from the project: ". $this->Project->ProjectUser->errorString());
		}
	}

}
