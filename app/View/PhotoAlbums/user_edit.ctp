<?= $this->element("Core.fileupload-js"); ?>
<?= $this->element("Sortable.js"); ?>

<? $id = !empty($this->request->data['PhotoAlbum']['id']) ? $this->request->data['PhotoAlbum']['id'] : null; ?>
<? #$project_title = $this->Admin->project('title'); ?>
<?= $this->assign("page_title", $id?"Update Photo Album":"Add Photo Album"); ?>

<? $this->start("title_controls"); ?>
<? if(!empty($id)) { ?>
	<?=$this->Html->back("View album",array('action'=>'view',$id)); ?>
<? } ?>
<? $this->end(); ?>

<div class="photoAlbums form">
<?php echo $this->Form->create('PhotoAlbum'); ?>
	<div class='row col-md-6'>
		<?= $this->Form->input('id'); ?>
		<?= $this->Form->hidden('project_id'); ?>
		<?= $this->Form->title('title', array('label'=>false,'placeholder'=>'New Photo Album Name','default'=>"New Photos ".$this->Time->mondy(),'class'=>'large')); ?>
		<?= $this->Form->input('description',array('label'=>false,'rows'=>3,'cols'=>50,'placeholder'=>'Add a description...')); ?>
	</div>
	<div class='row col-md-6'>
		<div class='right'>
			<?= $this->Form->save("Save Album"); ?>
		</div>
		<?= $this->Form->fileupload("Upload.file", "Add photos...", array('id'=>'UploadFile')); ?>
		<?= $this->Html->blink('move', "Resort", "javascript:void(0)", array('id'=>'sorter','class'=>'btn-primary')); ?>
		<div class='clear'></div>
	</div>
	<div class='row'>

	<div id="Photos" class='photolist'>
	<? if(!empty($this->Form->data['Photo'])) { ?>
		<? foreach($this->Form->data['Photo'] as $photo) { ?>
			<?= $this->element("../PhotoAlbums/view_photo", array('photo'=>$photo,'album'=>$this->request->data,'edit'=>1)); ?>
		<? } ?>
	<? } ?>
	</div>
	<div class='clear'></div>

	<script>
	$('#UploadFile').uploader('<?= Router::url(array('action'=>'upload')); ?>', { 
		target: 'Photos',
		append: {
			"data[Upload][photo_album_id]": $('#PhotoAlbumId').val() 
		} 
	}); 
	$('#sorter').sorter('#Photos', {controller: 'photos',axis: 'both'});
	</script>

	<div class='clear'></div>

<?php echo $this->Form->end(); ?>
</div>
