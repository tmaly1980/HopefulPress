<? $this->assign("page_title", "Add Project Member"); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->back("Project Users", array('action'=>'users','project_id'=>$pid)); ?>
<? $this->end("admin_controls"); ?>
<div class='form '>
<?= $this->Form->create("ProjectUser", array('json'=>true)); ?>
	<div class='overview'>
	Adding a user to this project allows them to contribute their own content.
	</div>

	<?= $this->Form->hidden("project_id", array('value'=>$pid)); ?>
	<?= $this->Form->input("user_id", array('label'=>'Select a user')); ?>
	<?= $this->Form->input("admin", array('legend'=>'Access Level','type'=>'radio','default'=>0,'options'=>array(0=>'Project Member',1=>'Project Administrator'))); ?>
	<div class='tip'>
	<p><b>Administrators</b> can update content contributed by any project member as well as add new members to the project. 
	</div>

	<?= $this->Form->save("Add user", array('modal'=>true)); ?>
<?= $this->Form->end(); ?>
</div>
