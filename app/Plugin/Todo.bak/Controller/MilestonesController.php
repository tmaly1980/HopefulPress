<?php
App::uses('BelongsToController', 'BelongsTo.Controller');
App::uses('TodoAppController', 'Todo.Controller');
/**
 * Milestones Controller
 *
 * @property Milestone $Milestone
 * @property PaginatorComponent $Paginator
 */
class MilestonesController extends BelongsToController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public $uses = array('Todo.Milestone');

	public function index() {
		$this->paginate = array(
			'order' => 'Release.title ASC, Milestone.finish_date, Milestone.start_date'
		);
		$this->Milestone->recursive = 1;

		$this->set('milestones', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (empty($id) || !$this->Milestone->exists($id)) {
			return $this->notFound();
		}
		$this->set('milestone', $this->read($id));
	}

	public function edit($id = null) {
		if (!empty($id) && !$this->Milestone->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Milestone->saveAll($this->request->data)) {
				$this->setSuccess('The milestone has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The milestone could not be saved.');
			}
		} else if(!empty($id)) {
			$this->request->data = $this->read($id);
		}
		$releases = $this->Milestone->Release->find('list');
		$statuses = $this->Milestone->getEnumValues('status');
		$this->set(compact('releases','statuses'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Milestone->id = $id;
		if (!$this->Milestone->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Milestone->delete()) {
			return $this->setSuccess('The milestone has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The milestone could not be deleted.',array('action'=>'index'));
		}
	}


	public function manager_index() {
		$this->Milestone->recursive = 1;
		$this->set('milestones', $this->Paginator->paginate());
	}

	public function manager_view($id = null) {
		if (empty($id) || !$this->Milestone->exists($id)) {
			return $this->notFound();
		}
		$this->set('milestone', $this->read($id));
	}

	public function manager_edit($id = null) {
		if (!empty($id) && !$this->Milestone->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Milestone->saveAll($this->request->data)) {
				$this->setSuccess('The milestone has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The milestone could not be saved.');
			}
		} else if(!empty($id)) {
			$this->request->data = $this->read($id);
		}
		$releases = $this->Milestone->Release->find('list');
		$this->set(compact('releases'));
	}

/**
 * manager_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function manager_delete($id = null) {
		$this->Milestone->id = $id;
		if (!$this->Milestone->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Milestone->delete()) {
			return $this->setSuccess('The milestone has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The milestone could not be deleted.',array('action'=>'index'));
		}
	}
}
