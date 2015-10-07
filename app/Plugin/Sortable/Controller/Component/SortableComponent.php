<?
class SortableComponent extends Component
{
	var $controller = null;
	var $categoryField = null;

	function initialize(Controller $controller)
	{
		$this->controller = $controller;
		parent::initialize($controller);
	}

	function sort($catid = null, $model = null, $defaultResponse = true)
	{
		error_log("SORT CID=$catid");
		$saves = array();

		if(empty($model))
		{
			$model = $this->controller->modelClass;
		}
		if(!empty($this->controller->data[$model]))
		{
			if(empty($this->categoryField))
			{
				$this->categoryField = Inflector::singularize(Inflector::tableize($model))."_category_id";
			}
	
			foreach($this->controller->data[$model] as $ix => $id)
			{
				error_log("SAVING $id @ # $ix");
				$this->controller->{$model}->id = $id;
				if(!$this->controller->{$model}->hasField("ix"))
				{
					error_log("$model DOESNT SEEM TO HAVE A SORTING FIELD 'ix', NO EFFECT");
				}
				$this->controller->{$model}->saveField("ix", $ix);
				if(!empty($this->categoryField) && $this->controller->{$model}->hasField($this->categoryField))
				{ # Set null, too.
					$this->controller->{$model}->saveField($this->categoryField, $catid);
				}
				$saves[$ix] = $id;
				#error_log("SAVING $id TO $ix, IN $catid");
			}
		} # Otherwise not given data, ie list is empty. Destination list is in charge of reassignment of items.
		else {
			error_log("EMPTY SORT! EACH ITEM MUST HAVE ID STARTING WITH '{$model}_' . Data=".print_r($this->controller->data,true));
		}

		if(!empty($defaultResponse))
		{
			header("Content-Type: application/json");
			echo json_encode($saves);
			exit(0);
		}
	}
}
