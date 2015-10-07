<?
# In main repo!
class EditableComponent extends Component
{
	var $controller = null;
	function startup(Controller $controller)
	{
		$this->controller = $controller;
		return parent::startup($controller);
	}

	function edit_field($field, $id = null)
	{
		# If file exists, load from THINGS/View/THING
		$schema = $this->controller->model()->schema($field);
		$this->controller->set("schema", $schema);
		$this->controller->set("field", $field);
		$this->controller->render("Editable.edit_field");
	}

	function editable($field, $id = null, $defaultResponse = true, $defaultHeader = true) # Record id passed.
	{
		if(empty($id)) # Get first. ie singleton
		{
			$id = $this->controller->{$this->controller->modelClass}->first_id(); # This is fine except on 'Site', which will fail, since there is no site_id field.
			error_log("ID GUESSED=$id");
		}
		$model = $this->controller->modelClass;
		$this->controller->{$model}->id = $id;
		#error_log("SETTING $model TO $id");
		#error_log("D=".print_r($this->controller->data,true));
		if(!empty($this->controller->data[$model][$field])) # Theoretically could save multiple fields, tho don't know why. may want to put field in url.
		{
			#error_log("SAVING $model -> $field TO=".$this->controller->data[$model][$field]);
			$this->controller->{$model}->set($field, $this->controller->data[$model][$field]);
			$this->controller->{$model}->save();
			# Calling save() will trigger any beforeSave() elsewhere, ie Projectable
		} 

		if($defaultHeader)
		{
			header("Content-Type: application/json"); # ALWAYS?
		}
		if($defaultResponse)
		{
			echo nl2br($this->controller->data[$model][$field]); # Value is expected.
			exit(0);
			# Eventually maybe return with formatted content. ie nl2br, mailto:, etc.
		} # Else, leave up to controller to format and exit.
		return $this->controller->data[$model][$field];
	}
}
