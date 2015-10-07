<? $pid = Configure::read("project_id"); ?>
<?= $this->assign("page_title", "Project Members"); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->is_project_admin()) { ?>
	<?= $this->Html->add("Add User", array('admin'=>1,'action'=>'user_add','project_id'=>$pid)); ?>
	<? if($this->Html->is_project_owner() || $this->Html->is_site_admin()) { ?>
		<?= $this->Html->blink('user',"Change Owner", array('admin'=>1,'action'=>'owner','project_id'=>$pid)); ?>
	<? } ?>
<? } ?>
	<?= $this->Html->back("View project", array('admin'=>false,'action'=>'view','project_id'=>$pid)); ?>
<? $this->end("admin_controls"); ?>
<div>
	<? if(empty($users)) { ?>
		<div class='nodata'>
			This project does not have any members yet.
		</div>
	<? } else { ?>
		<? foreach($projectUsers as $user) { ?>
			<div id='ProjectUser_<?= $user['User']['id'] ?>' class='padding10 hover_controls'>
				<?= $user['User']['full_name'] ?>
				<? if(!empty($user['ProjectUser']['admin'])) { ?>
				<b>(Administrator)</b>
				<? } ?>
				<?= $this->Html->blink("email", "", "mailto:".$user['User']['email'],array('class'=>'btn-sm btn-success','title'=>'Email '.$user['User']['email'])); ?>
				&nbsp;
				<? if($this->Html->is_project_admin()) { # XXX FIXME #Admin->site_admin() || $this->Admin->project_owner()) { # PROJECT OWNER or site admin ?>
					<?= $this->Html->mdelete("", array('action'=>'user_delete',$pid,$user['User']['id']), array('confirm'=>"Are you sure you want to remove this user from the project?")); ?>
				<? } ?>
			</div>
		<? } ?>
	<? } ?>
</div>
