<?= $this->element("Sortable.js"); ?>
<?= $this->element("Core.fileupload-js"); ?>

<? $this->assign("page_title", !empty($photoAlbum['PhotoAlbum']['title']) ? $photoAlbum['PhotoAlbum']['title'] : "Untitled Album" ); ?>
<? $this->set('crumbs', true); ?>
<? $id = $photoAlbum['PhotoAlbum']['id'] ?>
<?= $this->Form->hidden("PhotoAlbum.id", array('id'=>'PhotoAlbumId','value'=>$id)); ?>

<? $this->start("admin_controls_before"); ?>
	<?= $this->Html->blink('back', "", array('action'=>'index')); ?>
<? $this->end(); ?>

<? if($this->Html->can_edit()){#$photoAlbum['PhotoAlbum'])) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->edit("Update Album",array('user'=>1,'action'=>'edit',$id)); ?>
	<?#= $this->Form->fileupload("Upload.file", "Add photos...", array('id'=>'UploadFile')); ?>
	<?#= $this->Html->blink('move', "Resort", "javascript:void(0)", array('id'=>'sorter','class'=>'btn-primary')); ?>
	<?#= $this->Html->blink('delete', "Delete album", array('user'=>1,'action'=>'delete',$photoAlbum['PhotoAlbum']['id']),array('class'=>'btn-danger','confirm'=>'Are you sure you want to remove the album? All photos will be lost.')); #,'project_id'=>$photoAlbum['PhotoAlbum']['project_id'],'#'=>'AddPhotos'), array('class'=>'')); ?>
<? $this->end("title_controls"); ?>
<? } ?>

<div class="photoAlbums view">

<div align='right'>
	<?= !empty($photoAlbum['PhotoAlbum']['created']) ? $this->Time->mondy($photoAlbum['PhotoAlbum']['created']) : "" ?>
</div>

<p id="PhotoAlbum_Description">
	<?= $photoAlbum['PhotoAlbum']['description'] ?>
</p>

<hr/>

<div id="Photos">
<? if(empty($photoAlbum['Photo'])) { ?>
	<div class='nodata'>There are no photos in this album.</div>
<? } else { ?>
	<? foreach($photoAlbum['Photo'] as $photo) { 
		echo $this->element("../PhotoAlbums/view_photo", array('photo'=>$photo,'album'=>$photoAlbum));
	} ?>
<? } ?>
</div>

<? if($this->Html->can_edit($photoAlbum['PhotoAlbum'])) { ?>
	<script>
	$('#sorter').sorter('#Photos', {prefix: 'user', controller: 'photos',axis: 'both'});
	
	$('#UploadFile').uploader('<?= Router::url(array('user'=>1,'action'=>'upload')); ?>', { 
		target: 'Photos',
		append: {"data[Upload][photo_album_id]": $('#PhotoAlbumId').val() } 
		}
	); 
	$('#LayoutTitle').inline_edit({prefix: 'user',link: '', inline:true, model: 'PhotoAlbum',field:'title',class: 'margintop10'});
	$('#PhotoAlbum_Description').inline_edit({prefix: 'user',link: 'Add description/Edit description'});
	</script>
<? } ?>

<div class='clear'></div>

</div>
