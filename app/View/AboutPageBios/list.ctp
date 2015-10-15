<? if(!empty($aboutPageBios)) { ?>
<div class='biolist Xcol-md-9'>
<? foreach($aboutPageBios as $bio) { ?>
<div id="AboutPageBio_<?= $bio['AboutPageBio']['id'] ?>" class='row marginbottom25'>
	<div class='col-sm-3 center_align'>
		<? if($page_photo_id = $bio['AboutPageBio']['page_photo_id']) { ?>
			<?= $this->element("PagePhotos.thumb",array('wh'=>'200x200','id'=>$page_photo_id,'class'=>'maxwidth100p')); ?>
		<? } ?>
	</div>
	<div class='medium col-sm-4'>
		<b><?= $bio['AboutPageBio']['name'] ?><? if(!empty($bio['AboutPageBio']['title'])) { ?>, <i><?= $bio['AboutPageBio']['title'] ?></i><? } ?></b>
		&nbsp;
		<? if($this->Rescue->admin()) { ?>
		<?= $this->Html->edit(null, array('admin'=>1,'controller'=>'about_page_bios','action'=>'edit',$bio['AboutPageBio']['id']),array('class'=>'btn-xs ')); ?>
		<?= $this->Html->delete(null, array('admin'=>1,'controller'=>'about_page_bios','action'=>'delete',$bio['AboutPageBio']['id']),array('confirm'=>'Are you sure you want to remove this staff bio?','class'=>'btn-xs')); ?>
		<? } ?>
		<div class='clear'></div>
		<? if(!empty($bio['AboutPageBio']['description'])) { ?>
		<div>
			<?= $this->Text->autoLink(nl2br($bio['AboutPageBio']['description'])); ?>
		</div>
		<? } ?>
	</div>
</div>
<? } ?>
</div>
<? } ?>
