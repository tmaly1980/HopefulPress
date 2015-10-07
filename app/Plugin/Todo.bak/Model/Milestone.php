<?
App::uses("TodoModel", "Todo.Model");
class Milestone extends TodoModel
{
	#var $order = 'Milestone.finish_date, Milestone.start_date';

	var $actsAs = array(
		'Core.Dateable'=>array('fields'=>array('start_date','finish_date')),
	);
	var $belongsTo  = array(
		'Release'=>array('className'=>'Todo.Release','foreignKey'=>'release_id'),
	);

	var $hasMany = array(
		'Task'=>array('className'=>'Todo.Task','foreignKey'=>'milestone_id','order'=>'Task.module_id'),
	);

	function beforeFind($query)
	{
		if(!isset($query['conditions']["{$this->alias}.status"]) && !isset($query['conditions']['status']))  # Hide closed/deferred unless explicit
		{
			$query['conditions']["{$this->alias}.status NOT IN"] = array('Deferred','Closed');
		}
		return $query;
	}
}
