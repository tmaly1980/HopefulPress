<h2>New Foster Application</h2>

<p>A foster application has been submitted</p>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>true,'plugin'=>'rescue','controller'=>'fosters','action'=>'view',$foster['Foster']['id'],'full_base'=>true))); ?>

<p>Foster request information:

<?= $this->element("Rescue.../Fosters/details"); ?>


