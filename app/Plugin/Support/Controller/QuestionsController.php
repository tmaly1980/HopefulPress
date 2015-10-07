<?
class QuestionsController extends AppController
{
	var $uses = array('Support.Question','Support.QuestionComment','Support.QuestionNotifiction','Support.QuestionLike');

	function index()
	{
		# XXX TODO if cycling through pages of previous questions, don't bother showing current new questions...
		$this->set("questions", $this->Question->find('all',array('conditions'=>array("Question.status IS NULL OR Question.status != 'accepted'"))));
		$this->set("previous_questions", $this->paginate('Question',array("Question.status = 'accepted'")));
	}

	function categories()
	{
		$this->set("categories", $this->Question->QuestionCategory->find('all'));
		$this->set("uncategorized_count", $this->Question->count(array('question_category_id'=>null)));
	}
	function category($id=null)
	{
		if(!empty($id))
		{
			$this->set("category", $this->Question->QuestionCategory->read(null, $id));
		}
		$this->set("questions", $this->Question->find('all',array('question_category_id'=>$id)));
	}

	function user_subscribe($id) # Add notification
	{
		$me = $this->Auth->me();
		if(!$this->Question->QuestionNotification->count(array('question_id'=>$id,'user_id'=>$me)))
		{
			$this->Question->QuestionNotification->create();
			if($this->Question->QuestionNotification->save(array('QuestionNotification'=>array('question_id'=>$id,'user_id'=>$me))))
			{
				$this->setSuccess("You have been successfully subscribed to this question and will be notified of updates");
			} else { 
				$this->setError("Could not subscribe to this question");
			}
		}
		$this->redirect(array('action'=>'view',$id));
	}

	function user_unsubscribe($id) # Remove notification
	{
		$me = $this->Auth->me();
		if($this->Question->QuestionNotification->deleteAll(array('QuestionNotification.question_id'=>$id,'QuestionNotification.user_id'=>$me)))
		{
			$this->setSuccess("You have been successfully unsubscribed from this question and will no longer be notified of updates");
		}
		$this->redirect(array('action'=>'view',$id));
	}

	function user_comment($id) # Comment added/edited
	{
		if(!empty($this->request->data))
		{
			if($this->QuestionComment->save($this->request->data))
			{
				$question = $this->Question->read(null, $id);
				$this->notify($question, "New comment for support question", "Support.questions/comment",array('comment'=>$this->QuestionComment->read()));
				$this->redirect(array('manager'=>null,'user'=>null,'action'=>'view',$id,'#'=>'comment_'.$this->QuestionComment->id));
			} else {
				$this->setError("Cannot save comment. ".$this->QuestionComment->errorString());
			}
		}
		$this->redirect(array('manager'=>null,'user'=>null,'action'=>'view',$id));
	}

	function user_edit($id=null) # User adds question, updates question, or I (manager) adds answer.
	{
		if(!empty($this->request->data))
		{
			if($this->Question->saveAll($this->request->data))
			{
				$question = $this->Question->read();
				if(!empty($id))
				{
					if($this->Auth->user("manager") && !empty($this->request->data['Question']['answer']))
					{ # I've answered.
						# Set 'answered' timestamp if not already set. But never set if I create questions myself.
						if($question['Question']['user_id'] != $this->Auth->me() && empty($question['Question']['answered']))
						{
							$this->Question->saveField("answered", date('Y-m-d H:i:s'));
							$this->Question->saveField("answerer_user_id", $this->Auth->me());
						}

						$this->notify($question, "Support question answered", "Support.questions/answered");
					} else if(!$this->Auth->user("manager")) {
						$this->notify($question, "Support question updated", "Support.questions/updated");
					}
				} else if(!$this->Auth->user("manager")) { #  Notify manager, since new.
					$this->sendManagerEmail("NEW support question", "Support.questions/new", array('question'=>$question));
				}

				return $this->setSuccess("Question successfully ".($id?"updated":"submitted"), array('user'=>null,'manager'=>null,'action'=>'view',$this->Question->id));
			} else {
				return $this->setError("Could not ".($id?"update":"submit")." question. ".$this->Question->errorString());
			}
		} else if(!empty($id)) {
			$this->request->data = $this->Question->read(null,$id);
		}
	}

	function notify($question,$subject,$template,$vars=array())
	{
		$vars = array_merge(array('question'=>$question),$vars);

		$questioner = $question['Question']['user_id'];
		$me = $this->Auth->me();
		error_log("Q=".print_r($vars,true));

		# Notify everyone else interested
		$notified = Set::extract("/QuestionNotification/user_id", $question);
		$commenters = Set::extract("/QuestionComment/user_id", $question);
		$managers = $this->User->fields("id", array('User.manager'=>1));
		$everyone = array_unique(array_diff(array_merge($notified, $commenters, array($questioner)), array($me), $managers));

		if(!$this->Auth->user("manager"))  # fix if ever multiple managers
		{
			$this->sendManagerEmail($subject, $template, $vars);
		}

		if(!empty($everyone))
		{
			$this->sendEmail($everyone, $subject, $template, $vars);
		}
	}

	function manager_delete($id)
	{
		if($this->Question->delete($id))
		{
			$this->setSuccess("Question deleted");
		} else  {
			$this->setError("Could not delete question");
		}
		$this->redirect(array('manager'=>null,'action'=>'index'));
	}

	function view($id)
	{
		$this->Question->recursive = 2;
		$this->set("question", $question =  $this->Question->read(null,$id));
		$this->Question->saveField("views",$question['Question']['views']+1);
	}

	function user_status($id)
	{
		$this->Question->id = $id;
		if(!empty($this->request->data['Question']['status']))
		{
			$status = $this->request->data['Question']['status'];

			# Hopefully they left a comment to clarify problem...
			if(empty($this->request->data['QuestionComment'][0]['comment'])) { 
				unset($this->request->data['QuestionComment']); # Didn't bother.
			}
			if($this->Question->saveAll($this->request->data))
			{
				# Notify me... and other curious bystanders
				$question = $this->Question->read(null, $id);
				$vars = array('question'=>$question,'comment'=>(!empty($this->request->data['QuestionComment'][0])  ? $this->request->data['QuestionComment'][0]:null));
				if($status == 'accepted')
				{
					$this->notify($question, "Support answer accepted", "Support.questions/accepted", $vars); # Also notify others curious of answer
					return $this->setSuccess("Thanks for getting back to us. We're glad to help!", array('action'=>'view',$id));
				} else if ($status == 'rejected') {
					$this->sendManagerEmail("Support answer rejected", "Support.questions/rejected", $vars);
					return $this->setSuccess("Sorry to hear. We'll get back to you as soon as possible.", array('action'=>'view',$id));
				} # Else, I dunno.
			} 
		}
		return $this->setError("Oops. We weren't able to save your update.", array('action'=>'view',$id));
		# We didn't get any data from them. =(
	}

	function user_like($id)
	{
		if($this->QuestionLike->count(array('QuestionLike.question_id'=>$id,'QuestionLike.user_id'=>$this->Auth->me()))) # Removing/un-liking
		{
			$this->QuestionLike->deleteAll(array('QuestionLike.question_id'=>$id,'QuestionLike.user_id'=>$this->Auth->me()));
		} else { # Liking
			$this->QuestionLike->create();
			$this->QuestionLike->set("user_id",$this->Auth->me());
			$this->QuestionLike->set("question_id",$id);
			$this->QuestionLike->save();
		}
		$this->Question->recursive = 2;
		$question = $this->Question->read(null,$id);
		$this->set("question",$question);
	}

}
