<? $id = $newsPost['NewsPost']['id']; ?>
<? $this->set("crumbs", true); ?>
<? $this->assign("page_title", $newsPost['NewsPost']['title']); ?>
<? $this->set("share", true); ?>
<? $this->start("left_admin_controls"); ?>
	<?= $this->Html->back(null, array("action"=>"index")); ?>
<? $this->end("left_admin_controls"); ?>
<? $this->start("admin_controls"); ?>
	<? if($this->Html->can_edit($newsPost['NewsPost'])) { ?>
		<?= $this->Html->edit("Edit News Post", array('user'=>1,'action'=>'edit',$id)); ?>
	<? } ?>
<? $this->end("admin_controls"); ?>

<?#= $this->Publishable->publish(); ?>

<div class="newsPosts view fontify <?#= $this->Admin->fontsize('default'); ?>">

<div class="paddingbottom25">
	<?= $this->Time->mondyear($newsPost['NewsPost']['created']); ?>
</div>

	<?= $this->element("PagePhotos.view"); ?>
<p>
	<?= ($newsPost['NewsPost']['content']); ?>
</p>
<div class='clear'></div>

</div>
