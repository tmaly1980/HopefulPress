<? $id = !empty($this->request->data["VolunteerDownload"]["id"]) ? $this->request->data["VolunteerDownload"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Update Volunteer Download" : "Add Volunteer Download"); ?>
<div class="links form col-md-6">
<?php echo $this->Form->create('VolunteerDownload',array('type'=>'file')); # ?>
	<?= $this->Form->input('id'); ?>
	<?= $this->Form->input('file',array('type'=>'file','label'=>(empty($id)?'Choose a file from your computer':'Update your file (optional)'),
		'note'=>(!empty($id) ? "Leave blank to keep original file":""))); ?>
	<?= $this->Form->input('title',array()); ?>
	<?= $this->Form->input('description',array('type'=>'textarea','rows'=>3)); ?>

	<script>
	$('#VolunteerDownloadFile').change(function() {
		// Guess title and auto-fill if empty.
		if($('#VolunteerDownloadTitle').val()) { return; }
		var filename = $('#VolunteerDownloadFile').val();
		var title = filename.replace(/^.+[\\\/]/, "")
			.replace(/[.]\w+$/, "").
			underscore().
			replace(/\W+/g, "_").
			humanize().
			ucwords();
		$('#VolunteerDownloadTitle').val(title).focus();
	});

	</script>
	<?= $this->Form->save('Save Download');#, array('modal'=>true)); ?>
</div>
<?php echo $this->Form->end(); ?>
</div>
