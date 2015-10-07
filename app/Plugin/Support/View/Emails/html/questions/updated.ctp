<h2>A Support Question Has Been Updated</h2>

<p>A support question has been updated by  <?= $question['User']['full_name'] ?>

<p><?= $this->Html->link($question['Question']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>

<p><?= $this->Text->truncate($question['Question']['description']); ?>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>
