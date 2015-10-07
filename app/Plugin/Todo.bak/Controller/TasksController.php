<?php
App::uses('TodoAppController', 'Todo.Controller');
/**
 * Tasks Controller
 *
 * @property Task $Task
 * @property PaginatorComponent $Paginator
 */
class TasksController extends TodoAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$this->Task->recursive = 1;
		$cond = array();

		if(!empty($this->request->params['named']))
		{
			foreach($this->request->params['named'] as $k=>$v)
			{
				if($k == 'text')
				{
					$text = $v;
					$cond["OR"] = array('Task.title REGEXP'=>$text,'Task.description REGEXP'=>$text,'Task.impact REGEXP'=>$text);
				} else {
					$cond["Task.$k"] = $v;
				}
			}
		}

		if(empty($cond)) # Show via milestones, then tasks outside of any milestone.
		{
			$cond['Task.milestone_id'] = null;
			# 

			$this->set("milestones", $this->Task->Milestone->find('all',array('conditions'=>array('Milestone.status != "Closed"'),'order'=>'Release.title,Milestone.finish_date,Milestone.start_date','recursive'=>2)));
		}

		# NOT NEEDED, should be automatic inside of task beforeFind
		#if(!isset($cond['Task.status'])) { $cond['Task.status NOT IN'] = array('Deferred','Closed'); } # Only open.

		$this->set('tasks', $this->Paginator->paginate('Task',$cond));

		/*
		$this->set("modules", $m=$this->Task->Module->find('list'));
		$this->set("releases", $r=$this->Task->Release->find('list'));
		$this->set("milestones", $ms=$this->Task->Milestone->find('list'));
		$this->set("types", $t=$this->Task->getEnumValues("type"));
		$this->set("statuses", $s=$this->Task->getEnumValues("status"));
		$this->set("priorities", $p=$this->Task->getEnumValues("priority"));
		*/
	}

	public function view($id = null) {
		if (empty($id) || !$this->Task->exists($id)) {
			return $this->notFound();
		}
		$this->set('task', $this->read($id));
	}

	public function edit($id = null) {
		if (!empty($id) && !$this->Task->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Task->saveAll($this->request->data)) {
				$this->setSuccess('The task has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The task could not be saved.');
			}
		} else if(!empty($id)) {
			$this->request->data = $this->read($id);
		}
		$modules = $this->Task->Module->find('list');
		$parents = $this->Task->Parent->find('list');
		$milestones = $this->Task->Milestone->find('list');
		$releases = $this->Task->Release->find('list');
		$types  = $this->Task->getEnumValues('type');
		$statuses  = $this->Task->getEnumValues('status');
		$priorities  = $this->Task->getEnumValues('priority');
		$this->set(compact('modules','parents', 'milestones', 'releases','priorities','types','statuses'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Task->id = $id;
		if (!$this->Task->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Task->delete()) {
			return $this->setSuccess('The task has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The task could not be deleted.',array('action'=>'index'));
		}
	}


	public function manager_index() {
		$this->Task->recursive = 1;
		$this->set('tasks', $this->Paginator->paginate());
	}

	public function manager_view($id = null) {
		if (empty($id) || !$this->Task->exists($id)) {
			return $this->notFound();
		}
		$this->set('task', $this->read($id));
	}

	public function manager_edit($id = null) {
		if (!empty($id) && !$this->Task->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Task->save($this->request->data)) {
				$this->setSuccess('The task has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The task could not be saved.');
			}
		} else if(!empty($id)) {
			$this->request->data = $this->read($id);
		}
		$parents = $this->Task->Parent->find('list');
		$milestones = $this->Task->Milestone->find('list');
		$releases = $this->Task->Release->find('list');
		$this->set(compact('parents', 'milestones', 'releases'));
	}

/**
 * manager_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function manager_delete($id = null) {
		$this->Task->id = $id;
		if (!$this->Task->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Task->delete()) {
			return $this->setSuccess('The task has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The task could not be deleted.',array('action'=>'index'));
		}
	}
}
