<h2>Support ticket solution/fix REJECTED</h2>

<p>Your solution/fix to the support ticket has been REJECTED by <?= $ticket['User']['full_name'] ?>

<p><?= $this->Html->link($ticket['Ticket']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>

<? if(!empty($comment)) { ?>
<p><?= $ticket['User']['full_name'] ?>  says:
</p>
<p>
	<?= nl2br($comment['comment']); ?>
</p>
<? } ?>

<h4>Please fix the issue or ask the submitter for clarification</h4>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>
