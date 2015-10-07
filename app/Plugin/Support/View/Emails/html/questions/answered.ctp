<h2>An Answer For Your Support Question </h2>

<p>Your support question has been answered.

<p><?= $this->Html->link($question['Question']['title'], Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>

<p>
Please review the answer and let us know if you are satisfied, by clicking on 'Accept' or 'Reject' on the question page:

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>null,'plugin'=>'support','controller'=>'questions','action'=>'view',$question['Question']['id'],'full_base'=>true))); ?>
