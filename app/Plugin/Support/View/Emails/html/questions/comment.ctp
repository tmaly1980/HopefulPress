<h2>Someone commented on a support question</h2>

<p>Someone commented on the following support question:

<p><?= $this->Html->link($question['Question']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>

<? if(!empty($comment)) { ?>
<p><?= $comment['User']['full_name'] ?>  says:
</p>
<p>
	<?= nl2br($comment['QuestionComment']['comment']); ?>
</p>
<? } ?>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>
