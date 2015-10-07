<?php
App::uses('BlogAppController', 'Blog.Controller');
class TopicsController extends BlogAppController {

/* Actions */
	public function index() {
		$this->Tracker->track('Blog');
	 	#if (!$this->Topic->count()) { return $this->noContent(); }

		$this->Topic->recursive = 2;
		#$this->Topic->Posts->setHasMany('Subposts',array('class'=>'Blog.Post','foreignKey'=>'parent_id'));
		$topics = $this->Topic->find('all', array('conditions'=>array('draft'=>0)));
		if(true || empty($topics))
		{
			$this->redirect(array('controller'=>'posts','action'=>'index'));
		}
		$this->set('topics', $topics);
	}

	public function view($id = null) {
		$this->Tracker->track('Blog');
	 	if (!$this->Topic->count($id)) { return $this->invalid(); }
		#$this->Topic->Posts->setHasMany('Subposts',array('class'=>'Blog.Post','foreignKey'=>'parent_id'));
		$this->set('topic', $this->Topic->read(null, $id));
	}

	public function manager_index() {
	 	if (!$this->Topic->count()) { return $this->noContent(); }

		$this->Topic->recursive = 2;
		#$this->Topic->Posts->setHasMany('Subposts',array('class'=>'Blog.Post','foreignKey'=>'parent_id'));
		$this->set('topics', $this->Topic->find('all'));
	}

	public function manager_view($id = null) {
	 	if (!$this->Topic->count($id)) { return $this->invalid(); }
		#$this->Topic->Posts->setHasMany('Subposts',array('class'=>'Blog.Post','foreignKey'=>'parent_id'));
		$this->Topic->recursive = 2;
		$this->set('topic', $this->Topic->read(null, $id));
	}

	public function manager_edit($id = null) {
		if (!empty($this->request->data)) { # $this->request->is('post') || $this->request->is('put')) { # This bad code lets jquery topics add record even if nothing sent.
			# fix tags before save....
			# I may need to grab the tag's primary id or create the tag before (or after) saving the topic - since I'm adding on the fly.
			# bwfore 

			if ($this->Topic->save($this->request->data)) {
				$this->setFlash(__('The blog topic has been saved'),array('action'=>'index'));
			} else {
				$this->setFlash(__('The blog topic could not be saved. Please, try again.'));
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->Topic->read(null, $id);
		}
	}

	public function manager_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->check($id);
		if ($this->Topic->delete()) {
			$this->setFlash(__('Blog topic deleted'), array('action'=>'index'));
		}
		$this->Session->setFlash(__('Blog topic was not deleted'),array('action'=>'index'));
	}
}
