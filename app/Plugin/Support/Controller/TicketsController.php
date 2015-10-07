<?
class TicketsController extends AppController
{
	var $uses = array('Support.Ticket','Support.TicketComment','Support.TicketNotifiction');

	function index()
	{
		$this->set("tickets", $this->Ticket->find('all',array('conditions'=>array("Ticket.confirmed IS NULL AND Ticket.deferred IS NULL")))); 
		$this->set("previous_tickets", $this->paginate('Ticket',array("Ticket.confirmed IS NOT NULL")));
	}

	function user_subscribe($id) # Add notification
	{
		$me = $this->Auth->me();
		if(!$this->Ticket->TicketNotification->count(array('ticket_id'=>$id,'user_id'=>$me)))
		{
			$this->Ticket->TicketNotification->create();
			if($this->Ticket->TicketNotification->save(array('TicketNotification'=>array('ticket_id'=>$id,'user_id'=>$me))))
			{
				$this->setSuccess("You have been successfully subscribed to this ticket and will be notified of updates");
			} else { 
				$this->setError("Could not subscribe to this ticket");
			}
		}
		$this->redirect(array('action'=>'view',$id));
	}

	function user_unsubscribe($id) # Remove notification
	{
		$me = $this->Auth->me();
		if($this->Ticket->TicketNotification->deleteAll(array('TicketNotification.ticket_id'=>$id,'TicketNotification.user_id'=>$me)))
		{
			$this->setSuccess("You have been successfully unsubscribed from this ticket and will no longer be notified of updates");
		}
		$this->redirect(array('action'=>'view',$id));
	}

	function user_comment($id) # Comment added/edited
	{
		if(!empty($this->request->data))
		{
			if($this->TicketComment->save($this->request->data))
			{
				$ticket = $this->Ticket->read(null, $id);
				$this->notify($ticket, "New comment for support ticket", "Support.tickets/comment",array('comment'=>$this->TicketComment->read()));
				$this->redirect(array('manager'=>null,'user'=>null,'action'=>'view',$id,'#'=>'comment_'.$this->TicketComment->id));
			} else {
				$this->setError("Cannot save comment. ".$this->TicketComment->errorString());
			}
		}
		$this->redirect(array('manager'=>null,'user'=>null,'action'=>'view',$id));
	}

	function user_edit($id=null) # User adds ticket, updates ticket, or I (manager) resolves.
	{
		if(!empty($this->request->data))
		{
			if($this->Ticket->saveAll($this->request->data))
			{
				$ticket = $this->Ticket->read();
				if(!empty($id))
				{
					$this->notify($ticket, "Support ticket updated", "Support.tickets/updated");
				} else if(!$this->Auth->user("manager")) { #  Notify manager, since new.
					$this->sendManagerEmail("NEW support ticket", "Support.tickets/new", array('ticket'=>$ticket));
				}

				return $this->setSuccess("Ticket successfully ".($id?"updated":"submitted"), array('user'=>null,'manager'=>null,'action'=>'view',$this->Ticket->id));
			} else {
				return $this->setError("Could not ".($id?"update":"submit")." ticket. ".$this->Ticket->errorString());
			}
		} else if(!empty($id)) {
			$this->request->data = $this->Ticket->read(null,$id);
		}
	}

	function notify($ticket,$subject,$template,$vars=array())
	{
		$vars = array_merge(array('ticket'=>$ticket),$vars);

		$ticketer = $ticket['Ticket']['user_id'];
		$me = $this->Auth->me();

		# Notify everyone else interested
		$notified = Set::extract("/TicketNotification/user_id", $ticket);
		$commenters = Set::extract("/TicketComment/user_id", $ticket);
		$managers = $this->User->fields("id", array('User.manager'=>1));
		$everyone = array_unique(array_diff(array_merge($notified, $commenters, array($ticketer)), array($me), $managers));

		if(!$this->Auth->user("manager"))  # fix if ever multiple managers
		{
			$this->sendManagerEmail($subject, $template, $vars);
		}

		if(!empty($everyone))
		{
			$this->sendEmail($everyone, $subject, $template, $vars);
		}
	}

	function manager_delete($id)
	{
		if($this->Ticket->delete($id))
		{
			$this->setSuccess("Ticket deleted");
		} else  {
			$this->setError("Could not delete ticket");
		}
		$this->redirect(array('manager'=>null,'action'=>'index'));
	}

	function view($id)
	{
		$this->Ticket->recursive = 2;
		$this->set("ticket", $ticket =  $this->Ticket->read(null,$id));
	}

	function user_status($id)  # Submitter accept/reject solution, or manager changing status.
	{
		$this->Ticket->id = $id;
		if(!empty($this->request->data))
		{
			# Hopefully they left a comment to clarify problem...
			if(empty($this->request->data['TicketComment'][0]['comment'])) { 
				unset($this->request->data['TicketComment']); # Didn't bother.
			}
			if($this->Ticket->saveAll($this->request->data))
			{
				# Notify me... and other curious bystanders
				$ticket = $this->Ticket->read(null, $id);
				$vars = array('ticket'=>$ticket,'comment'=>(!empty($this->request->data['TicketComment'][0])  ? $this->request->data['TicketComment'][0]:null));

				if($this->Auth->user("manager"))
				{
					if(!empty($this->request->data['Ticket']['resolved']))
					{
						$this->Ticket->saveField("tech_user_id", $this->Auth->me());
						$ticket = $this->Ticket->read();
						$this->notify($ticket, "Support ticket resolved", "Support.tickets/resolved");
					} else if(!empty($this->request->data['Ticket']['estimated'])) {
						$this->Ticket->saveField("tech_user_id", $this->Auth->me());
						$ticket = $this->Ticket->read();
						$this->notify($ticket, "Support ticket assigned", "Support.tickets/assigned");
					} else if(!empty($this->request->data['Ticket']['deferred'])) {
						$this->notify($ticket, "Support ticket deferred", "Support.tickets/deferred");
					}
					return $this->setSuccess("The ticket has been updated.", array('action'=>'view',$id));
				}
				else if(!empty($this->request->data['Ticket']['confirmed']))
				{
					$this->notify($ticket, "Support ticket solution accepted", "Support.tickets/confirmed", $vars); # Also notify others curious of answer
					return $this->setSuccess("Thanks for getting back to us. We're glad to help!", array('action'=>'view',$id));
				} else if(isset($this->request->data['Ticket']['resolved']) && empty($this->request->data['Ticket']['resolved']))  {
					$this->sendManagerEmail("Support ticket solution rejected", "Support.tickets/rejected", $vars);
					return $this->setSuccess("Sorry to hear. We'll get back to you as soon as possible.", array('action'=>'view',$id));
				} # No other notifications.
			} 
		}
		return $this->setError("Oops. We weren't able to save your update.", array('action'=>'view',$id));
		# We didn't get any data from them. =(
	}

}
