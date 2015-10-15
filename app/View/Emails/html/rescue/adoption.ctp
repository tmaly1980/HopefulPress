<h2>New Adoption Application</h2>

<p>An adoption application has been submitted</p>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>true,'controller'=>'adopters','action'=>'view',$adopter['Adopter']['id'],'full_base'=>true))); ?>

<p>Adoption request information:

<?= $this->element("../Adopters/details"); ?>


