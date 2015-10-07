<?
App::uses("SupportAppModel", "Support.Model");

class Ticket extends SupportAppModel
{
	var $actsAs = array(
		'Core.Dateable'=>array( # Date conversion.
			'fields'=>array('estimated','resolved','confirmed','deferred'),
			'format'=>"M j, Y", # Jan 13, 2002
		)
	);

	var $order = "Ticket.modified DESC";

	var $belongsTo  = array(
		"Tech"=>array( 'className'=>'User','foreignKey'=>'tech_user_id'),
		"User" # Ticketer
	);

	var $hasMany = array(
		"TicketComment"=>array('className'=>'Support.TicketComment','foreignKey'=>'ticket_id'),
		"TicketNotification"=>array('className'=>'Support.TicketNotification','foreignKey'=>'ticket_id'),
	);

	var $hasAndBelongsToMany = array(
	);
}
