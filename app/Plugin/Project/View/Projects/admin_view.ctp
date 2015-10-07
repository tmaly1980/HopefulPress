<? $this->set("back", false); ?>
<? $pid = !empty($project['Project']['id']) ? $project['Project']['id'] : null; ?>
<? $this->start("post_titlebar"); ?>
	<?= $this->Publishable->publish(); ?>
<? $this->end(); ?>

<? $this->start("access_controls"); ?>
<div id="ProjectUsers">
	<?= $this->element("../Projects/admin_users"); ?>
</div>
<? $this->end(); ?>

<? $this->start("presidebar"); ?>

<!--
<div id="project_add_links">
	<?= $this->Html->link("<span>Add a page</span>", array('controller'=>'pages','action'=>'add','project_id'=>$pid), array('class'=>'add_button width175 block')); ?>
	<?= $this->Html->link("<span>Add a news post</span>", array('controller'=>'news_posts','action'=>'add','project_id'=>$pid), array('class'=>'add_button width175 block')); ?>
	<?= $this->Html->link("<span>Add an event</span>", array('controller'=>'events','action'=>'add','project_id'=>$pid), array('class'=>'add_button width175 block')); ?>
	<?= $this->Html->link("<span>Add photos</span>", array('controller'=>'photo_albums','action'=>'add','project_id'=>$pid), array('class'=>'add_button width175 block')); ?>

	<?= $this->Html->link("<span>Add a link</span>", array('controller'=>'links','action'=>'index','project_id'=>$pid,'?'=>array('add'=>1,'project_id'=>$pid)), array('class'=>'add_button width175 block')); ?>
	<?= $this->Html->link("<span>Add a download</span>", array('controller'=>'downloads','action'=>'index','project_id'=>$pid,'?'=>array('add'=>1,'project_id'=>$pid)), array('class'=>'add_button width175 block')); ?>
	<?= $this->Html->link("<span>Add a video</span>", array('controller'=>'videos','action'=>'index','project_id'=>$pid,'?'=>array('add'=>1,'project_id'=>$pid)), array('class'=>'add_button width175 block')); ?>
	<?= $this->Html->link("<span>Add an audio clip</span>", array('controller'=>'videos','action'=>'index','project_id'=>$pid,'?'=>array('add'=>1,'project_id'=>$pid)), array('class'=>'add_button width175 block')); ?>
</div>
-->

<? $this->end(); ?>
<?= $this->element("../Projects/view"); ?>
<?= $this->Admin->tiplist("Managing Your Project"); ?>
