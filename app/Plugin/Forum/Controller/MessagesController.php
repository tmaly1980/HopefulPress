<?
App::uses("ForumAppController", "Forum.Controller");
class MessagesController extends ForumAppController
{
	public $uses = array('Forum.Message','Forum.MessageLike');

	# Adding will work thru here also....
	function user_edit($id=null)
	{
		$me = $this->Auth->me();

		if(!empty($this->request->data))
		{
			if($this->Message->saveAll($this->request->data))
			{
				$message = $this->Message->read();
				$did = $this->request->data['Discussion']['id'];
				$this->Message->Discussion->recursive = 2;
				$discussion = $this->Message->Discussion->read(null, $did);
				$messages = $discussion['Message'];

				$vars = array(
					'discussion'=>$discussion,
					'message'=>$message
				);

				# Notify manager.
				if(!$this->Auth->user("manager"))
				{
					$this->managerEmail("Forum message", "forum/message", $vars);
				}

				# Notify other participants.
				foreach($messages as $message)
				{
					$mid = $message['id'];
					$muid = $message['user_id'];
					if($me !== $muid && empty($message['User']['manager'])) # Not self, or managers
					{
						$email = $message['User']['email'];
						$this->userEmail($email, "Forum message", "forum/message", $vars);
						$this->Message->id = $mid;
						$this->Message->saveField("notified",1); # So sent once until read.
					}
				}

				return $this->redirect(array('controller'=>'discussions','action'=>'view',$this->Message->Discussion->id,'#'=>"message_".$this->Message->id));
			} else {
				return $this->setError("Could not save message");
			}
		} else if(!empty($id)) { 
			$this->request->data = $this->Message->read(null, $id);
		}
	}

	function user_delete($id)
	{
		if(empty($id)) { return $this->setError("No message specified for deletion",array('controller'=>'discussions','action'=>'index')); }

		# MUST be owner OR me
		$cond = array('Message.id'=>$id);
		if(!$this->Auth->user('manager')) {
			$cond['user_id'] = $this->Auth->me();
		}
		$this->Message->id = $id;
		$did = $this->Message->field("discussion_id");
		if($this->Message->deleteAll($cond))
		{
			return $this->setSuccess("Message deleted", array('controller'=>'discussions','action'=>'view',$did));
		} else {
			return $this->setError("Could not delete message", array('controller'=>'discussions','action'=>'view',$did));
		}
	}

	function user_like($id)
	{
		if($this->MessageLike->count(array('MessageLike.message_id'=>$id,'MessageLike.user_id'=>$this->Auth->me()))) # Removing/un-liking
		{
			error_log("DELETING PREVIOUS LIKE...");
			$this->MessageLike->deleteAll(array('MessageLike.message_id'=>$id,'MessageLike.user_id'=>$this->Auth->me()));
		} else { # Liking
			$this->MessageLike->create();
			$this->MessageLike->set("user_id",$this->Auth->me());
			$this->MessageLike->set("message_id",$id);
			$this->MessageLike->save();
		}
		$this->Message->recursive = 2;
		$message = $this->Message->read(null,$id);
		$message['Message']['Like'] = $message['Like'];
		$this->set("message",$message['Message']);
	}

}
