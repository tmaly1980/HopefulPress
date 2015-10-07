<h2>Website Inquiry</h2>

<p>A new website inquiry was submitted:

<p><?= $this->Html->link(Router::url(array('manager'=>1,'plugin'=>'www','controller'=>'intake_surveys','action'=>'view',$intakeSurvey['IntakeSurvey']['id']),true)); ?>
