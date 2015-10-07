<?
# For EventLocation, LinkCategory, etc dropdowns/inputs
class BelongsToController extends AppController
{
	# XXX TODO someday move all this into a component so we can still re-use plugin controller/etc if need be.
	function view($id)
	{
		$this->set($this->thingVar(), $this->{$this->modelClass}->read(null, $id));
	}

	function select($id = null)
	{
		$items = $this->{$this->modelClass}->find('list');
		if(empty($items)) { $this->setAction("add"); } # redirect will mess up whole page.
		$this->set($this->thingVars(), $items);
		$this->set("selected", $id);
	}

	function add()
	{
		$this->set($this->thingVars(), $this->{$this->modelClass}->find('list'));
	}

	function delete($id = null)
	{
		# Assume 'thing' is first part of classname, removed, ie LinkCategory => Category
		$table = Inflector::tableize($this->modelClass);
		$ucThing = Inflector::humanize(preg_replace("/^[^_]+_/", "", $table));
		$thing = strtolower($thing);
		if($this->{$this->modelClass}->delete($id))
		{
			return $this->setSuccess("$ucThing removed",array('action'=>'index'));
		} else {
			return $this->setError("Could not remove $thing");
		}
	}

	function edit($id = null) # For updating details to a belongsTo object.... probably in a modal dialog.
	{
		error_log("AJAX_UPDATING=".$this->ajax_updating());
		# If updating header isnt passed then we will get list page.
		if(!empty($this->request->data) && $this->ajax_updating())
		{
			error_log("SAVING=".print_r($this->data,true));
			if($this->{$this->modelClass}->save($this->data))
			{
				$id = $this->{$this->modelClass}->id;
				return $this->setAction("select", $id);
			} else {
				$this->Json->error("Could not save location");
				return $this->Json->render();
			}
		}
		$items = $this->{$this->modelClass}->find('list');
		$this->set($this->thingVars(), $items); # So we can say/not 'or select an existing'

		if(!empty($id))
		{
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}
	}
}
?>
