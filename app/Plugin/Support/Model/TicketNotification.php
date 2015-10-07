<?
App::uses("SupportAppModel", "Support.Model");

class TicketNotification extends SupportAppModel
{
	var $belongsTo  = array(
		"Ticket"=>array('className'=>"Support.Ticket"),
		"User"
	);

}
