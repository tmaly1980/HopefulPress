<?php
App::uses('TodoAppController', 'Todo.Controller');
App::uses('BelongsToController', 'BelongsTo.Controller');
/**
 * Modules Controller
 *
 * @property Module $Module
 * @property PaginatorComponent $Paginator
 */
class ModulesController extends BelongsToController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$this->Module->recursive = 1;
		$this->set('modules', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (empty($id) || !$this->Module->exists($id)) {
			return $this->notFound();
		}
		$this->set('module', $this->read($id));
	}

	public function edit($id = null) {
		if (!empty($id) && !$this->Module->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Module->saveAll($this->request->data)) {
				$this->setSuccess('The module has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The module could not be saved.');
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
		$this->Module->id = $id;
		if (!$this->Module->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Module->delete()) {
			return $this->setSuccess('The module has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The module could not be deleted.',array('action'=>'index'));
		}
	}


	public function manager_index() {
		$this->Module->recursive = 1;
		$this->set('modules', $this->Paginator->paginate());
	}

	public function manager_view($id = null) {
		if (empty($id) || !$this->Module->exists($id)) {
			return $this->notFound();
		}
		$this->set('module', $this->read($id));
	}

	public function manager_edit($id = null) {
		if (!empty($id) && !$this->Module->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Module->saveAll($this->request->data)) {
				$this->setSuccess('The module has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The module could not be saved.');
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
		$this->Module->id = $id;
		if (!$this->Module->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Module->delete()) {
			return $this->setSuccess('The module has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The module could not be deleted.',array('action'=>'index'));
		}
	}
}
