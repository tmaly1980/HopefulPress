<?= $this->element("Sortable.js"); ?>
<?= $this->element("Core.fileupload-js"); ?>

<div id='sort_msg' class='alert alert-info' style='display:none;'>
	Drag your pictures around to reorganize, then click the 'Done' button above.
</div>
<div id="Photos" class='row'>
<? if(empty($adoptable['Photos'])) { ?>
	<div class='nodata col-md-12'>There are no photos for this adoptable.</div>
<? } else { ?>
	<? foreach($adoptable['Photos'] as $photo) { 
		echo $this->element("../AdoptablePhotos/item", array('photo'=>$photo)); 
	} ?>
<? } ?>
</div>

<? if($this->Html->me()) { ?>
	<script>
	$('#sorter').sorter('#Photos', {msg: '#sort_msg', prefix: 'user', controller: 'adoptable_photos',axis: 'both'});

	<? $adoptable_id = !empty($this->request->data['Adoptable']['id']) ? $this->request->data['Adoptable']['id'] : null; ?>
	$('#UploadFile').uploader('<?= Router::url(array('user'=>1,'controller'=>'adoptable_photos','action'=>'listupload',$adoptable_id,'rescue'=>$rescuename)); ?>', 
		{ 
		target: 'Photos',
		}
	); 
	</script>
<? } ?>

<div class='clear'></div>

