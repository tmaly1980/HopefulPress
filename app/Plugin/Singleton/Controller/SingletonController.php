<?
class SingletonController extends AppController
{
	var $autocreate = true;

	function user_enable()
	{
		if(!$this->model()->hasField("disabled"))
		{
			$this->redirect(array('action'=>'add'));
		} else {
			if($this->model()->count())
			{
				$this->model()->saveField("disabled", 0);
			} else {
				$this->model()->singleton();
			}
			$this->redirect(array('action'=>'index'));
		}
	}

	function user_disable()
	{
		if(!$this->model()->hasField("disabled"))
		{
			$this->redirect(array('action'=>'delete'));
		} else {
			if($this->model()->count())
			{
				$this->model()->saveField("disabled", 1);#date("Y-m-d H:i:s"));
			} # Else, not there, no issue.
			$this->redirect(array('action'=>'index'));
		}
	}

	function view()
	{
		$data = null;
		if(empty($this->autocreate))
		{
			if($this->{$this->modelClass}->count())
			{
				$data = $this->{$this->modelClass}->first();
			} 
		} else {
			$data = $this->{$this->modelClass}->singleton();
		}
		$this->set($this->{$this->modelClass}->thingVar(), $data);
	}

	function edit()
	{
		if($this->_edit())
		{
			$this->redirect(array('action'=>'view'));
		}
	}

	function _edit($id =null)
	{
		$this->{$this->modelClass}->id = $this->{$this->modelClass}->first_id();  # SET FIRST  so we know (ie field_default) whether existing/new record

		if(!empty($this->request->data))
		{
			# We should try to SET ->id FIRST, s
			error_log("SAVING {$this->modelClass}=".print_r($this->request->data,true));
			if($this->{$this->modelClass}->save($this->request->data))
			{
				$this->setSuccess($this->ucThing()." updated");
				return true;
			}
			# Else fail.
			$this->setError("Cannot update ".$this->thing());
		}

		$this->request->data = $this->{$this->modelClass}->singleton();

		return false;
	}

	function index()
	{
		$this->redirect(array('action'=>'view'));
	}
}
