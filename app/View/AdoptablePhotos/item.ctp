<? $ix = !empty($this->request->data['ix']) ? $this->request->data['ix'] : ''; ?>
<? if(!empty($photo['Photos'])) { $photo = $photo['Photos']; } ?>
<? if(!empty($photo['filename'])) { # Skip broken/bogus pics ?>
<div id="AdoptablePhoto_<?= $photo['id'] ?>" class='Photo col-md-2 maxheight125 center_align'>
	<?= $this->Form->hidden("Photos.$ix.id", array('value'=>$photo['id'])); ?>
	<? $img = $this->Html->image(array('controller'=>'adoptable_photos','action'=>'thumb',$photo['id'],'120x120',1), array('class'=>'border')); ?>
	<?= $this->Html->link($img, array('controller'=>'adoptable_photos','action'=>'original',$photo['id']), array('class'=>'lightbox','title'=>$photo['caption'])); ?><br/>

<? if(!empty($this->request->data['Adoptable']) && $this->Html->me()) { ?>
	<div style='position: absolute; top: 5px; right: 5px;' class=''>

		<?= $this->Html->blink("delete", '', array('controller'=>'adoptable_photos','action'=>'delete',$photo['id'],'ext'=>'json'), array('id'=>'PhotoDelete'.$photo['id'],'class'=>'controls json btn-xs btn-danger', 'confirm'=>'Are you sure you want to remove this photo?','title'=>'Delete this photo','e'=>0)); ?>
	</div>
	<!-- add/edit caption from large photo page; FORGET ROTATE -->
<? } ?>
</div>
<? } ?>
