<h2>Your support ticket has been assigned</h2>

<p><?= $this->Html->link($ticket['Ticket']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>

<p><?= $ticket['Tech']['full_name'] ?> has been assigned to your ticket and has estimated resolution before <?= date("D M j", strtotime($ticket['Ticket']['estimated'])); ?>. We will be in touch if we need further information.

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>
