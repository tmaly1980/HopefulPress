<?php
App::uses('AppController', 'Controller');
class NewsPostsController extends AppController {
	var $components = array(
		#'Publishable.Publishable'
	);

/* Actions */

	public function index() {
		error_log("INDEX");
		$this->track();
		#if (!$this->NewsPost->count()) { return $this->notFound(); }
		$this->NewsPost->recursive = 0;

		$this->set('newsPosts', 
			#$this->Publishable->paginate() # Drafts are exposed if Configure::in_admin 
			$this->paginate() # Drafts are exposed if Configure::in_admin 
		);
	}

	public function view($id = null) {
		error_log("VIEW=$id");
		$this->track();
		if (!$this->NewsPost->count($id)) { return $this->invalid(); }
		$this->set('newsPost', $this->NewsPost->read(null, $id));
	}

	/*
	public function admin_index() {
		if (!$this->NewsPost->count()) { return $this->noContent(); }

		# Retrieve total, published and unpublished count.
		$this->Publishable->getTotals();

		# XXX TODO see if we're trying to filter pub/unpub only.
		$this->Publishable->filter();

		$this->NewsPost->recursive = 0;
		$this->set('newsPosts', $this->paginate());

		$this->set("latest", $this->{$this->modelClass}->find('latest'));
	}

	public function admin_view($id = null) {
		if (!$this->NewsPost->count($id)) {
			return $this->invalid();
		}
		$this->set('newsPost', $this->NewsPost->read(null, $id));
	}
	*/

	public function edit($id = null) {
		error_log("EDIT=$id");
		if ($this->request->is('post') || $this->request->is('put')) {
			#if ($this->NewsPost->draft($this->request->data)) {
			if ($this->NewsPost->save($this->request->data)) {
				#$this->savedFlash();
				#$this->view_redirect($this->NewsPost->id);
				#$this->redirect(array('action'=>'view',$this->NewsPost->id));
				error_log("SUPPOSED TO GO TO VIEW");
				$this->setSuccess('The news post has been saved',array('action'=>'view',$this->NewsPost->id));
			} else {
				$this->setError('The news post could not be saved: '.$this->NewsPost->errorString());
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->NewsPost->read(null, $id);
		}
		$this->set("users", $this->User->find('list'));
	}

}
