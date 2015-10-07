<h2>Support question answer accepted</h2>

<p>Your answer to the support question has been accepted by <?= $question['User']['full_name'] ?>

<p><?= $this->Html->link($question['Question']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>

<? if(!empty($comment)) { ?>
<p><?= $question['User']['full_name'] ?>  says:
</p>
<p>
	<?= nl2br($comment['comment']); ?>
</p>
<? } ?>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>
