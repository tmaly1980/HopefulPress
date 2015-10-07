<h2>Your support ticket issue has been fixed</h2>

<p><?= $this->Html->link($ticket['Ticket']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>

<p>Please double check that your issue has been resolved, and let us know if you are satisfied, by clicking on the buttons on the ticket page:

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>
