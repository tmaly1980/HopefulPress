<h2>A Support Ticket Has Been Updated</h2>

<p>A support ticket has been updated by  <?= $ticket['User']['full_name'] ?>

<p><?= $this->Html->link($ticket['Ticket']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>

<p><?= $this->Text->truncate($ticket['Ticket']['description']); ?>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'tickets','action'=>'view',$ticket['Ticket']['id'],'full_base'=>true))); ?>
