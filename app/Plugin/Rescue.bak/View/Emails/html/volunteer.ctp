<h2>New Volunteer Application</h2>

<p>A volunteer application has been submitted</p>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>true,'plugin'=>'rescue','controller'=>'volunteers','action'=>'view',$volunteer['Volunteer']['id'],'full_base'=>true))); ?>

<p>Volunteer request information:

<?= $this->element("Rescue.../Volunteers/details"); ?>


