<h2>You have been given access to a project</h2>

<p><?= $sender['name'] ?> has given you access to the project "<?= $project['Project']['title'] ?>". 

<p>Projects can have their own pages, news, events, photos, links and downloads.

<? if(!empty($projectUser['ProjectUser']['admin'])) { ?>
<p>You have been given <b>administrator</b> access, allowing you to change other project members' content.
<? } else { ?>
<p>You will be able to create and edit your own project content with your level of access.
<? } ?>

<p>View the project to begin:

<p><?= $this->Html->link(Router::url(array('admin'=>true,'controller'=>'projects','action'=>'view',$project['Project']['id'], 'full_base'=>true))); ?>
