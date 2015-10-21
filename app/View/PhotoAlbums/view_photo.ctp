<? $ix = !empty($this->request->data['ix']) ? $this->request->data['ix'] : 0; ?>
<? if(!empty($photo['Photo'])) { $photo = $photo['Photo']; } # ... ?>
<? if(!empty($photo['filename'])) { # Skip broken/bogus pics ?>
<div id="Photo_<?= $photo['id'] ?>" class='left margintop5 marginright15 height150 relative'>
	<?= $this->Form->hidden("Photo.$ix.id", array('value'=>$photo['id'])); ?>
	<? $img = $this->Html->image(array('controller'=>'photos','action'=>'thumb',$photo['id']), array('class'=>'border')); ?>
	<?= !empty($edit) ? $img : $this->Html->link($img, array('controller'=>'photos','action'=>'fullimage',$photo['id'],'photo_album_id'=>$photo['photo_album_id']), array('title'=>$photo['caption'],'class'=>'lightbox')); ?><br/>

<? if(!empty($edit)) { #$this->Html->can_edit()){ #$album['PhotoAlbum'])) { ?>
<div class='center_align'>
	<?= $this->Html->edit("Edit caption",array('user'=>1,'controller'=>'photos','action'=>'edit_caption',$photo['id']),array('title'=>'Edit caption','class'=>'btn-xs btn-primary dialog')); ?>

</div>

	<div style='position: absolute; top: 5px; right: 5px;' class=''>

		<?= $this->Html->blink("delete", '', array('controller'=>'photos','action'=>'delete',$photo['id'],'ext'=>'json'), array('id'=>'PhotoDelete'.$photo['id'],'class'=>'json btn-xs btn-danger', 'Xconfirm'=>'Are you sure you want to remove this photo?','title'=>'Delete this photo','e'=>0)); ?>
	</div>
	<!-- add/edit caption from large photo page; FORGET ROTATE -->
<? } ?>
</div>
<? } ?>
