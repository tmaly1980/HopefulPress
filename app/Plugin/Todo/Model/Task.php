<?
App::uses("TodoModel", "Todo.Model");
class Task extends TodoModel
{
	var $order = '%ALIAS%.modified DESC';
	var $actsAs = array(
		'Core.Dateable'=>array('fields'=>array('due_date','resolved')),
	);
	var $belongsTo  = array(
		'Module'=>array('className'=>'Todo.Module','foreignKey'=>'module_id'),
		'Parent'=>array('className'=>'Todo.Task','foreignKey'=>'parent_id'),
		'Milestone'=>array('className'=>'Todo.Milestone','foreignKey'=>'milestone_id'),
		'Release'=>array('className'=>'Todo.Release','foreignKey'=>'release_id'),
	);

	function beforeSave($options=array())
	{
		# Get previous data.
		$beforeStatus = !empty($this->id) ? $this->field('status') : null;

		if(!empty($this->data[$this->alias]['status']))
		{
			$status = $this->data[$this->alias]['status'];
			if($status != $beforeStatus) # Changed. Set field.
			{
				$dateField = strtolower(Inflector::slug($status))."_date";
				error_log("STATUS $beforeStatus => $status, DATE=$dateField");
				if($this->hasField($dateField))
				{
					$this->data[$this->alias][$dateField] = date('Y-m-d H:i:s');
				}
			}
		}
		return true;
	}

	function beforeFind($query)
	{
		if(!isset($query['conditions']["{$this->alias}.status"]) && !isset($query['conditions']['status']))  # Hide closed/deferred unless explicit
		{
			$query['conditions']["{$this->alias}.status NOT IN"] = array('Deferred','Closed');
		}
		return $query;
	}
}
