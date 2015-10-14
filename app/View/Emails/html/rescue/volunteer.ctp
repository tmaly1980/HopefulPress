<h2>New Volunteer Application</h2>

<p>A volunteer application has been submitted</p>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>true,'controller'=>'rescue_volunteers','action'=>'view',$rescueVolunteer['RescueVolunteer']['id'],'full_base'=>true))); ?>

<p>Volunteer request information:

<?= $this->element("../RescueVolunteers/details"); ?>


