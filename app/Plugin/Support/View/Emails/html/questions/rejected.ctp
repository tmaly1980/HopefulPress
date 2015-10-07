<h2>Support Question REJECTED</h2>

<p>Your answer to the support question has been REJECTED by <?= $question['User']['full_name'] ?>

<p><?= $this->Html->link($question['Question']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>

<p><?= $this->Text->truncate($question['Question']['description']); ?>

<? if(!empty($comment)) { ?>
<p><?= $question['User']['full_name'] ?>  says:
</p>
<p>
	<?= nl2br($comment['comment']); ?>
</p>
<? } ?>

<h4>Please correct your answer or ask the submitter for clarification</h4>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>
