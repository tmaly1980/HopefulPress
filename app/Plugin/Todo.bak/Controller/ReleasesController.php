<?php
App::uses('TodoAppController', 'Todo.Controller');
/**
 * Releases Controller
 *
 * @property Release $Release
 * @property PaginatorComponent $Paginator
 */
App::uses('BelongsToController', 'BelongsTo.Controller');
class ReleasesController extends BelongsToController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$this->Release->recursive = 1;
		$this->set('releases', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (empty($id) || !$this->Release->exists($id)) {
			return $this->notFound();
		}
		$this->set('release', $this->read($id));
	}

	public function edit($id = null) {
		if (!empty($id) && !$this->Release->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Release->save($this->request->data)) {
				$this->setSuccess('The release has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The release could not be saved.');
			}
		} else if(!empty($id)) {
			$this->request->data = $this->read($id);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Release->id = $id;
		if (!$this->Release->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Release->delete()) {
			return $this->setSuccess('The release has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The release could not be deleted.',array('action'=>'index'));
		}
	}


	public function manager_index() {
		$this->Release->recursive = 1;
		$this->set('releases', $this->Paginator->paginate());
	}

	public function manager_view($id = null) {
		if (empty($id) || !$this->Release->exists($id)) {
			return $this->notFound();
		}
		$this->set('release', $this->read($id));
	}

	public function manager_edit($id = null) {
		if (!empty($id) && !$this->Release->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Release->save($this->request->data)) {
				$this->setSuccess('The release has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The release could not be saved.');
			}
		} else if(!empty($id)) {
			$this->request->data = $this->read($id);
		}
	}

/**
 * manager_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function manager_delete($id = null) {
		$this->Release->id = $id;
		if (!$this->Release->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Release->delete()) {
			return $this->setSuccess('The release has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The release could not be deleted.',array('action'=>'index'));
		}
	}
}
