<? $tooManyDaysInvited = 4; # Once we display danger ?>
<? $this->assign("page_title", "Users"); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->add("Add a user", array('action'=>'add')); ?>
	<? if($this->Html->is_site_owner()) { ?>
		<?= $this->Html->settings("Settings", array('action'=>'settings')); ?>
	<? } ?>
	<?#= $this->Html->blink('import',"Import users (CSV)", array('action'=>'import')); ?>
<? $this->end(); ?>

<div class='index row'>
<? if(empty($users)) { ?>
	<div class='nodata'>No user accounts</div>
<? } else { ?>
	<? foreach($users as $user) { ?>
	<div class='col-md-3 col-sm-4 col-xs-6 height250'>
		<div>
		<? if(!empty($user['User']['page_photo_id'])) { ?>
			<?= $this->element("PagePhotos.image",array('id'=>$user['User']['page_photo_id'],'wh'=>'150x150','title'=>$user['User']['full_name'],'href'=>array('action'=>'edit',$user['User']['id']))); ?>
		<? } else { ?>
			<?= $this->Html->link($this->Html->image("/images/person.png",array('width'=>150)),array('action'=>'edit',$user['User']['id'])); ?>
		<? } ?>
		</div>
		<?= $this->Html->link($user['User']['full_name'], array('action'=>'edit',$user['User']['id']),array('class'=>'medium bold')); ?>
		<div><?= $this->Html->glink('email',$user['User']['email'], "mailto:".$user['User']['email']); ?></div>
		<? if(!empty($user['User']['admin'])) { ?>
			<div class='bold'>Administrator</div>
		<? } ?>
		<? if($user['User']['id'] === $current_site['Site']['user_id']) { ?>
			<div class='bold'>Site Owner</div>
		<? } ?>
	</div>
	<? } ?>
<? } ?>
</div>
