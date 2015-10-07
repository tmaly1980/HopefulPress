<?
App::uses("TodoModel", "Todo.Model");
class Module extends TodoModel
{
	var $hasMany = array(
		'Task'=>array('className'=>'Todo.Task','foreignKey'=>'module_id'),
	);
}
