<?php
App::uses('BlogAppController', 'Blog.Controller');
/**
 * Subscribers Controller
 *
 * @property Subscriber $Subscriber
 * @property PaginatorComponent $Paginator
 */
class SubscribersController extends BlogAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function index() {
		$this->Subscriber->recursive = 1;
		$this->set('subscribers', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (empty($id) || !$this->Subscriber->exists($id)) {
			return $this->notFound();
		}
		$this->set('subscriber', $this->read($id));
	}

	public function subscribe() {
		if (!empty($this->request->data)) { 
			if ($this->Subscriber->save($this->request->data)) {
				$this->Tracker->track("Blog");
				$subscriber = $this->Subscriber->read();
				$this->view = 'subscribe_confirm';
				$email = $subscriber['Subscriber']['email'];
				$this->email($email, "Confirm email subscription", "subscribers/subscribe",array('subscriber'=>$subscriber));
			}
		}
	}

	public function subscribe_confirm($email)
	{
		$subscriber = $this->Subscriber->findByEmail($email);
		$this->Subscriber->id = $subscriber['Subscriber']['id'];
		$this->Subscriber->saveField("confirmed", 1);
		return $this->setSuccess('Your email subscription has been confirmed.', "/");
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($email)
	{
		$subscriber = $this->Subscriber->findByEmail($email);
		if (empty($subscriber))
		{
			return $this->notFound();
		}
		$this->email($email, "Confirm email removal", "subscribers/delete",array('subscriber'=>$subscriber));
		$this->setSuccess("An email has been sent to complete your unsubscription from our mailing list", "/");
	}

	function delete_confirm($email)
	{
		$this->Subscriber->deleteAll(array('email'=>$email));
		return $this->setSuccess('Your email has been removed from our mailing list.', "/");
	}


	public function manager_index() {
		$this->Subscriber->recursive = 1;
		$this->set('subscribers', $this->Paginator->paginate());
	}

	public function manager_view($id = null) {
		if (empty($id) || !$this->Subscriber->exists($id)) {
			return $this->notFound();
		}
		$this->set('subscriber', $this->read($id));
	}

	public function manager_edit($id = null) {
		if (!empty($id) && !$this->Subscriber->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this->Subscriber->save($this->request->data)) {
				$this->setSuccess('The subscriber has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The subscriber could not be saved.');
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
		$this->Subscriber->id = $id;
		if (!$this->Subscriber->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this->Subscriber->delete()) {
			return $this->setSuccess('The subscriber has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The subscriber could not be deleted.',array('action'=>'index'));
		}
	}
}
