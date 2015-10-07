<h2>Support ticket solution confirmed/accepted</h2>

<p>The support ticket solution has been accepted by <?= $ticket['User']['full_name'] ?>

<p><?= $this->Html->link($ticket['Ticket']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>

<? if(!empty($comment)) { ?>
<p><?= $ticket['User']['full_name'] ?>  says:
</p>
<p>
	<?= nl2br($comment['comment']); ?>
</p>
<? } ?>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>
