<?
# AboutPageBios, Links, Downloads, etc wrapper.
class ListItemsController extends AppController
{
	# NO VIEW(), since just a list of items.

	function index()
	{
		$this->set($this->thingVars(), $this->{$this->modelClass}->findAll());
	}

	function edit($id = null)
	{
		if(!empty($this->request->data))
		{
			if(!$this->{$this->modelClass}->saveAll($this->request->data)) # If any belongsTo (ie category), will save/create as well.
			{
				return $this->setError("Could not save ".$this->thing().": ".$this->{$this->modelClass}->errorString());
			}

			return $this->setSuccess("The ".$this->thing()." has been saved", array('action'=>'index'));
		}
		if(!empty($id)) { $this->request->data = $this->{$this->modelClass}->read(null, $id); }
	}

	function delete($id)
	{
		if(!$this->{$this->modelClass}->delete($id))
		{
			return $this->setError("Could not delete ".$this->thing().": ".$this->{$this->modelClass}->errorString());
		}

		return $this->setSuccess("The ".$this->thing()." has been deleted", array('action'=>'index'));
	}

}
