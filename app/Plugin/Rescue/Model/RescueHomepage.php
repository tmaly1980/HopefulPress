<?
App::uses("RescueAppModel", "Rescue.Model");
class RescueHomepage extends RescueAppModel
{
	var $tablePrefix = ''; # So we call as RescueHomepage  instead.

	var $actsAs  = array(
		'Singleton.Singleton',
	);

}
