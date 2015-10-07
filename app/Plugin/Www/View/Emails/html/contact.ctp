<h2>Website Contact Form</h2>

<p>Someone contacted you through the contact page/form:

<p><?= $this->Html->link(Router::url(array('manager'=>1,'plugin'=>'www','controller'=>'contact_requests','action'=>'view',$contactRequest['ContactRequest']['id']),true)); ?>
