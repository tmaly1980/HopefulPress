<? $id = !empty($this->request->data["AdoptionDownload"]["id"]) ? $this->request->data["AdoptionDownload"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Update Adoption Download" : "Add Adoption Download"); ?>
<div class="links form col-md-6">
<?php echo $this->Form->create('AdoptionDownload',array('type'=>'file')); # ?>
	<?= $this->Form->input('id'); ?>
	<?= $this->Form->input('file',array('type'=>'file','label'=>(empty($id)?'Choose a file from your computer':'Update your file (optional)'),
		'note'=>(!empty($id) ? "Leave blank to keep original file":""))); ?>
	<?= $this->Form->input('title',array()); ?>
	<?= $this->Form->input('description',array('type'=>'textarea','rows'=>3)); ?>

	<script>
	$('#AdoptionDownloadFile').change(function() {
		// Guess title and auto-fill if empty.
		if($('#AdoptionDownloadTitle').val()) { return; }
		var filename = $('#AdoptionDownloadFile').val();
		var title = filename.replace(/^.+[\\\/]/, "")
			.replace(/[.]\w+$/, "").
			underscore().
			replace(/\W+/g, "_").
			humanize().
			ucwords();
		$('#AdoptionDownloadTitle').val(title).focus();
	});

	</script>
	<?= $this->Form->save('Save Download');#, array('modal'=>true)); ?>
</div>
<?php echo $this->Form->end(); ?>
</div>
