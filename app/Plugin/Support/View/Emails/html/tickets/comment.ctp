<h2>Someone commented on a support ticket</h2>

<p>Someone commented on the following support ticket:

<p><?= $this->Html->link($ticket['Ticket']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>

<? if(!empty($comment)) { ?>
<p><?= $comment['User']['full_name'] ?>  says:
</p>
<p>
	<?= nl2br($comment['TicketComment']['comment']); ?>
</p>
<? } ?>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>
