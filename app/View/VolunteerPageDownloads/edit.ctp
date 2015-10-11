<? $id = !empty($this->request->data["VolunteerPageDownload"]["id"]) ? $this->request->data["VolunteerPageDownload"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Update VolunteerPage Download" : "Add VolunteerPage Download"); ?>
<div class="links form col-md-6">
<?php echo $this->Form->create('VolunteerPageDownload',array('type'=>'file')); # ?>
	<?= $this->Form->input('id'); ?>
	<?= $this->Form->input('file',array('type'=>'file','label'=>(empty($id)?'Choose a file from your computer':'Update your file (optional)'),
		'note'=>(!empty($id) ? "Leave blank to keep original file":""))); ?>
	<?= $this->Form->input('title',array()); ?>
	<?= $this->Form->input('description',array('type'=>'textarea','rows'=>3)); ?>

	<script>
	$('#VolunteerPageDownloadFile').change(function() {
		// Guess title and auto-fill if empty.
		if($('#VolunteerPageDownloadTitle').val()) { return; }
		var filename = $('#VolunteerPageDownloadFile').val();
		var title = filename.replace(/^.+[\\\/]/, "")
			.replace(/[.]\w+$/, "").
			underscore().
			replace(/\W+/g, "_").
			humanize().
			ucwords();
		$('#VolunteerPageDownloadTitle').val(title).focus();
	});

	</script>
	<?= $this->Form->save('Save Download');#, array('modal'=>true)); ?>
</div>
<?php echo $this->Form->end(); ?>
</div>
