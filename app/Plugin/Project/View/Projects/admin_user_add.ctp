<script>j.modaltitle("Add User to Project");</script>
<div class='form width400'>
<?= $this->Form->create("ProjectUser", array('json'=>true)); ?>
	<div class='overview'>
	Adding a user to this project allows them to add content to the project pages.
	</div>

	<?= $this->Form->hidden("project_id", array('value'=>$pid)); ?>
	<?= $this->Form->input("user_id", array('label'=>'Select a user')); ?>
	<?#= $this->Form->belongsTo_input("user_id"); ?>
	<?= $this->Form->input("administrator", array('legend'=>'Access Level','type'=>'radio','default'=>0,'options'=>array(0=>'Project Member',1=>'Project Administrator'))); ?>
	<div class='tip'>
	<b>Members</b> can add and update their own content within the project.

	<p><b>Administrators</b> can update, edit, or delete anybody's content within the project.
	</div>

	<?= $this->Form->save("Add user", array('modal'=>true)); ?>
<?= $this->Form->end(); ?>
</div>
