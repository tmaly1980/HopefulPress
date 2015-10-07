<h2>Your support ticket has been deferred</h2>

<p><?= $this->Html->link($ticket['Ticket']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>

<p>We're unable to work on your support ticket and will take a better look at your issue at a future date. The tentative date is by <?= date("M j", strtotime($ticket['Ticket']['deferred'])); ?>.

<? if(!empty($ticket['Ticket']['reason'])) { ?>
<p>Further explanation:

<p><?= $ticket['Ticket']['reason']; ?></p>
<? } ?>

<p>We apologize for this inconvenience and hope for your understanding.

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>
