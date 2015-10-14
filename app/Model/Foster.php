<?
App::uses("Volunteer","Model");
class Foster extends Volunteer # PROFILE/APPLICATION
{
	var $hasMany = array(
		'RescueFoster', # Hopefully on a rescue, this will filtr to just to THAT rescue's record.
	);

}
