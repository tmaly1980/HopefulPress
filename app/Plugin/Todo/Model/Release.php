<?
App::uses("TodoModel", "Todo.Model");
class Release extends TodoModel
{
	var $order = "Release.title ASC";
	var $actsAs = array(
		'Core.Dateable'=>array('fields'=>array('launch_date')),
	);
	var $hasMany = array(
		'Task'=>array('className'=>'Todo.Task','foreignKey'=>'release_id'),
		'Milestone'=>array('className'=>'Todo.Milestone','foreignKey'=>'release_id'),
	);
}
