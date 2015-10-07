<?
class HpCommentsController extends HpCommentsAppController
{
	var $name = "HpComments";
	var $title = "Comments";
	var $paginate = array(
		'limit'=>25,
		'order'=>'HpComment.id DESC'
	);
	var $layout = 'ajax';

	function comments($model, $model_id) # Ajaxy
	{
		error_log("LOADING COMMENTS=$model, $model_id");

		# Convert model name if needed.
		$model = Inflector::classify($model);

		# SHOW LAST 25
		#
		# Flat list, unmoderated.
		$comments = array_reverse($this->paginate("HpComment", array('model'=>$model, 'model_id'=>$model_id)));
		# Get last 25 by ordering in reverse chrono and then flipping.

		$this->set("comments", $comments);

		$this->set("modelClass", $model); # Since ajax, we need to pass.
		$this->set("model_id", $model_id);

		if(empty($this->request->data))
		{
			$this->request->data = array('HpComment'=>array());
		}

		$this->request->data['HpComment']['model'] = $model;
		$this->request->data['HpComment']['model_id'] = $model_id;

		# MAY as well reset whole container with larger list.
		# Need to use fancy pager, so we show FULL list and not just small chunk at a time.
	}

	function admin_comments($model, $model_id) { return $this->setAction("comments", $model, $model_id); }
	function manager_comments($model, $model_id) { return $this->setAction("comments", $model, $model_id); }

	function manager_add() { 
		if(!empty($this->data))
		{
			if($this->HpComment->save($this->data))
			{
				# XXX TODO ? maybe notify users in comments list ?

				# APPEND to list.
				$id = $this->HpComment->id;
				$this->set("comment", $this->HpComment->read(null, $id));
				$this->Json->set("append", true);
				$this->Json->set("update", "HpComments");
				error_log("RENDERING VIEW");
				return $this->Json->render("../HpComments/view");
				# Silent, just show and move to.
			} else {
				$this->Json->error("Could not submit comment. Please try again.");
			}
		}
		# Else, show form.


	}

	function admin_add()
	{
		$this->add();
	}

	function add() # Model and model_id are set in form.
	{
		if(!empty($this->data))
		{
			if($this->HpComment->save($this->data))
			{
				# Notify manager 
				# this assumes we're only using comments for internal use.
				$modelClass = $this->data['HpComment']['model'];
				$id = $this->data['HpComment']['model_id'];
				$thing = $this->HpComment->thing($modelClass);

				$this->loadModel($modelClass);
				$controller = Inflector::pluralize(Inflector::underscore($modelClass));

				$vars = array(
					'thing'=>$thing,
					'modelClass'=>$modelClass,
					'controller'=>$controller,
					'thingData'=>$this->{$modelClass}->read(null, $id),
					'comment_id'=>$this->HpComment->id

				);
				if(!$this->is_god())
				{
					$this->sendManagerEmail("Someone commented on a $thing", "manager/comment_added", $vars);
				}

				# APPEND to list.
				$id = $this->HpComment->id;
				$this->set("comment", $this->HpComment->read(null, $id));
				$this->Json->set("script", "j('#modal').scrollBottom();");
				$this->Json->set("append", true);
				$this->Json->set("update", "HpComments");
				error_log("RENDERING VIEW");
				return $this->Json->render("../HpComments/view");
				# Silent, just show and move to.
			} else {
				$this->Json->error("Could not submit comment. Please try again.");
			}
		}
		# Else, show form.
	}

	function admin_delete($comment_id) # Ajax.
	{
		$this->HpComment->id = $comment_id;
		$owner_id = $this->HpComment->field('user_id');
		if(((!empty($owner_id) && $owner_id == $this->get_user_id()) || $this->is_god()) && $this->HpComment->delete()) # Self.
		{
			$this->Json->set("script", "j('#HpComment_$comment_id').slideUp('slow').fadeOut('slow').remove();");
			$this->Json->set("status", "OK");
		} else { # non-existent, or not owner
			$this->Json->set("status", "FAIL");
		}

		return $this->Json->render();
	}

	function manager_delete($comment_id)
	{
		return $this->admin_delete($comment_id);
		/*
		$this->HpComment->id = $comment_id;
		if($this->HpComment->delete()) 
		{
			$this->Json->set("script", "j('#HpComment_$comment_id').slideUp().fadeOut().remove();");
			$this->Json->set("status", "OK");
		} else { # non-existent, or not owner
			$this->Json->set("status", "FAIL");
		}

		return $this->Json->render();
		*/
	}
}
