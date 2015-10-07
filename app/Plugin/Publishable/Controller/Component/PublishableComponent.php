<?
class PublishableComponent extends Component
{
	var $controller = null;
	var $modelClass = null;

	function model()
	{
		return $this->controller->{$this->controller->modelClass};
	}

	function startup(Controller $controller)
	{
		$this->controller = $controller;
		$this->modelClass = $controller->modelClass;
		# AUTOMAGICALLY add publishable behavior to model.
		$this->model()->Behaviors->attach("Publishable.Publishable"); # Implements draft_id so drafts not visible
		#$this->controller->{$this->modelClass}->Behaviors->attach("Publishable.SoftDeletable");
		$this->controller->helpers[] = "Publishable.Publishable";
		# If important to publish, important to not lose permanently...
	}

	function publish($id, $redirect = true)
	{
		$thing = $this->model()->thing();
		$things = Inflector::pluralize($thing);
		$controller = $this->controller->request->params['controller'];

		$pid = $this->model()->hasField("project_id") ? 
			$this->model()->field("project_id",array('id'=>$id)) : null;

		if($id = $this->model()->publish($id))
		{
			if($redirect)
			{
				$this->controller->setSuccess("The $thing has been published. <a href='".Router::url(array('action'=>'add','project_id'=>$pid))."' class='color green'>Add another $thing</a> or <a href='".Router::url(array('action'=>'index'))."' class='color'>view all $things</a>".(!empty($pid) ? " or <a href='".Router::url(array('controller'=>'projects','action'=>'view',$pid))."'>go back to the project</a>":""));
				$this->controller->redirect(array('action'=>'view',$id,'project_id'=>$pid));
			}
		} else {
			$this->setError("The thing could not be published. Please contact your administrator.",array('action'=>'view',$id,'project_id'=>$pid));

		}
	}

	function unpublish($id)
	{
		$thing = $this->model()->thing();
		$this->model()->unpublish($id);
		$this->controller->setSuccess("This $thing has been unpublished. You can publish it again at any time when you want others to see it.");
		$pid = $this->controller->{$this->modelClass}->field("project_id", array('id'=>$id));
		$this->controller->redirect(array('action'=>'view',$id,'project_id'=>$pid));
	}

	function getTotals()
	{
		$model = $this->controller->{$this->modelClass};
		$publishedCount = $model->findPublishedCount();
		$unpublishedCount = $model->findUnpublishedCount();
		$allCount = $model->find('count');

		$this->controller->set("statusPublishedCount", $publishedCount);
		$this->controller->set("statusUnpublishedCount", $unpublishedCount);
		$this->controller->set("statusAllCount", $allCount);
	}

	function filter() # Require paginate to be used.
	{
		$model = $this->controller->{$this->controller->modelClass};
		$conditions = array();
		if(isset($this->controller->request->params['named']['published']))
		{
			$conditions[] = $this->controller->request->params['named']['published'] ? 
				"{$model->alias}.published IS NOT NULL" : 
				"{$model->alias}.published IS NULL" ; 
			$this->controller->Paginator->settings['conditions'] = $conditions;
		}
		return $conditions; # Also in case NOT paginated....
	}

	#######################

	function autosave()
	{
		error_log("AUTOSAVING....");
		error_log("D=".print_r($this->controller->data,true));
		if(!empty($this->controller->data))
		{
			$docstatus = null;
			# XXX TODO Make sure drafting is enabled.... this should automatically go to a draft instead of the live version....

			# if published, autosave changes shouldnt be immediately published until say so.
			# so,
			#
			# if unpublished, autosave should 
			#
			# move to trash should work on ?draft? ?published? both?
			#
			# 
			$status = 'draft';
			# XXX TODO ALTER TO exclude parent record.... (hide their.id=my.draft_id)

			if($data = $this->controller->{$this->controller->modelClass}->draft($this->controller->data))
			{
				#error_log("DD=".print_r($data,true));
				$this->controller->Json->set("id", $this->controller->{$this->controller->modelClass}->id);
				$docstatus = "Draft saved at ".date("g:ia").". ";
				error_log("      DRAFT FOR {$data[$this->controller->modelClass]['draft_id']}");
				if(!empty($data[$this->controller->modelClass]['draft_id']))
				{
					$draft_id = $data[$this->controller->modelClass]['draft_id'];
					$original = $this->controller->{$this->controller->modelClass}->read(null, $draft_id);
					if(!empty($original))
					{
						$docstatus .= " Last edited";
						if(!empty($original['User']['name'])) {
							$docstatus .= " by {$original['User']['name']}";
						}
						$docstatus .= " on ".date("M j, Y g:ia", strtotime($original[$this->controller->modelClass]['modified']));
					}
					$this->controller->Json->set("draft_id", $draft_id);
				}
			} else {
				$docstatus = "Autosave failed at ".date('M j, Y g:i:sa') . ": ".$this->model()->errorString();
			}
			$this->controller->Json->set("status", $status);
			$this->controller->Json->set("statusName", Inflector::humanize($status));
			$this->controller->Json->set("docstatus", $docstatus);
		} else {
			error_log("AUTOSAVE, NO DATA");
		}
		$this->controller->Json->set("status", "ok");
		$this->controller->Json->debug = true;
		return $this->controller->Json->render();
	}

	function delete_draft($id)
	{
		$this->controller->{$this->controller->modelClass}->deleteAll(array('draft_id'=>$id));
		$this->controller->setSuccess("Draft revisions have been deleted.", array('action'=>'edit',$id));
	}


	#########################3
	# Filtering read() - for edit() calls

	function read($id) # Replace model read() so we divert to draft if it has one. And notify them of how to get to original master.
	{
		$Model = $this->controller->{$this->modelClass};

		error_log("ADMIN_READ=$id");
		$master = !empty($this->controller->params['named']['master']);

		$thing = $this->controller->{$this->modelClass}->thing();
		# This is backwards.... since 108 is the master and 109 is the draft, but on 108's page we're saying it's the draft...

		# Give them the record they asked for, just warn them if there is another one to possibly edit.
		if($Model->hasField("draft_id"))
		{
			# See if this has a draft, if so, load that record.
			$draft = $Model->first(array('draft_id'=>$id));
			if(!empty($draft))
			{
				$delete_url = Router::url(array('action'=>'delete_draft',$id));
				$this->controller->setInfo("You're currently editing a draft revision of this $thing. <a class='underline' href='$delete_url'>Delete the draft</a> to erase your changes");
				return $draft;
			}
		}

		return $Model->read(null, $id);
	}

	# Paginate, for index() (revisions/drafts are hidden)
	function paginate($model = null, $conditions = array())
	{
		if(empty($model)) { $model = $this->model(); }
		if(!Configure::read("in_admin"))
		{
			$conditions["{$model->alias}.draft_id"] = null;
		}
		return $this->controller->Paginator->paginate($model, $conditions);
	}

}
