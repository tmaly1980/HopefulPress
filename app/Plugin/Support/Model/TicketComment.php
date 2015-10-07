<?
App::uses("SupportAppModel", "Support.Model");

class TicketComment extends SupportAppModel
{
	var $belongsTo  = array(
		"Ticket"=>array('className'=>"Support.Ticket"),
		"User"
	);

}
