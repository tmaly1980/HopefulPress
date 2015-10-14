<h2>New Foster Application</h2>

<p>A foster application has been submitted</p>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>true,'controller'=>'rescue_fosters','action'=>'view',$rescueFoster['RescueFoster']['id'],'full_base'=>true))); ?>

<p>Foster request information:

<?= $this->element("../RescueFosters/details"); ?>


