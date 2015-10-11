<?
class RescueVolunteer extends AppModel
{
	var $order = "RescueVolunteer.status ASC";

	# DROPDOWNS
	var $statuses = array('Applied'=>'Applied','Active'=>'Active','Inactive'=>'Inactive','Rejected'=>'Rejected');#Received','Pending','Accepted','Denied');

	var $belongsTo = array(
		'User',
		'Rescue',
	);

}
