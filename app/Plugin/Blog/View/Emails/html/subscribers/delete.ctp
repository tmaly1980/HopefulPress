<h2>Mailing list removal</h2>

<p>Please click on the following link to remove your email from our mailing list:

<p><?= $this->Html->link(Router::url(array('admin'=>null,'plugin'=>'blog','controller'=>'subscribers','action'=>'delete_confirm',$subscriber['Subscriber']['email'],'full_base'=>true)),null); ?>
