<h2>You have been assigned ownership of a project</h2>

<p><?= $sender['name'] ?> has given you ownership of the project "<?= $project['Project']['title'] ?>". 

<p>Projects can have their own pages, news, events, photos, links and downloads. As the project owner, you'll be able to add and update all project-related content, as well as grant other users the ability to contribute to the project.</p>

<p>View the project to begin:

<p><?= $this->Html->link(Router::url(array('admin'=>true,'controller'=>'projects','action'=>'view',$project['Project']['id'], 'full_base'=>true))); ?>
