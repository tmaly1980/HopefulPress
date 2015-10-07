<h2>Mailing list confirmation</h2>

<p>Please click on the following link to confirm your email subscription to our mailing list:

<p><?= $this->Html->link(Router::url(array('admin'=>null,'plugin'=>'blog','controller'=>'subscribers','action'=>'subscribe_confirm',$subscriber['Subscriber']['email'],'full_base'=>true)),null); ?>
