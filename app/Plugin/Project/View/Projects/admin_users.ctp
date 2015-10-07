<? $pid = !empty($project['Project']['id']) ? $project['Project']['id'] : null; ?>
<h3>
	Project Members
</h3>
<div>
	<? if(empty($project['ProjectUser'])) { ?>
		<div class='nodata'>
			This project does not have any members yet.
		</div>
	<? } else { ?>
		<? foreach($project['ProjectUser'] as $user) { ?>
			<div id='ProjectUser_<?= $user['User']['id'] ?>' class='padding10 hover_controls'>
				<?= $user['User']['name'] ?>
				<? if($this->Admin->site_admin() || $this->Admin->project_owner()) { # PROJECT OWNER or site admin ?>
				<div class='left controls'>
					&nbsp;&nbsp;
					<?= $this->Html->link("Remove", array('action'=>'user_delete',$pid,$user['User']['id']), array('class'=>'color red','confirm'=>"Are you sure you want to remove this user from the project?",'json'=>1)); ?>
				</div>
				<? } ?>
			</div>
		<? } ?>
	<? } ?>
</div>
<? if($this->Admin->site_admin() || $this->Admin->project_owner()) { # PROJECT OWNER or site admin ?>
	<?= $this->Html->link("+ Add a user", array('action'=>'user_add',$pid), array('class'=>'color green modal')); ?>
<? } ?>
