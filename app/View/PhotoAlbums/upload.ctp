<?= $this->Html->script("Core.jquery-fileupload/jquery.fileupload"); ?>
<?= $this->Html->script("Core.uploader"); ?>

<? $id = !empty($this->request->data['PhotoAlbum']['id']) ? $this->request->data['PhotoAlbum']['id'] : null; ?>
<div id='AddPhotos' class='form border lightgreybg padding15 Photo item'>
	<!-- we cannot have anything else named data[Photo][...] because save album will think it's a photo entry in saveAll -->
	<?= $this->Form->input("Upload.file", array('multiple'=>'multiple','type'=>'file','label'=>'Add photos from your computer')); ?>
	<script>$('#UploadFile').uploader('<?= Router::url(array('action'=>'upload')); ?>', { target: 'Photos' }); </script>
	<!-- omitting $id on upload, so we dont add before album save -->
</div>
