<?
App::uses("RescueVolunteer", "Model");
class RescueFoster extends RescueVolunteer # Application for specific rescue.
{

	var $belongsTo = array(
		'Rescue',
		'User',
		'Foster',
	);

}
