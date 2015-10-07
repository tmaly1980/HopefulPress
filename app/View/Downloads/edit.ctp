<? $id = !empty($this->request->data["Download"]["id"]) ? $this->request->data["Download"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Update Download" : "Add Download"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->blink("back", "View Downloads Page", "/links"); ?>
<? $this->end(); ?>
<div class="links form col-md-6">
<?php echo $this->Form->create('Download',array('type'=>'file')); ?>
	<?= $this->Form->input('id'); ?>
	<?= $this->Form->hidden('project_id'); ?>
	<?= $this->Form->input('file',array('type'=>'file','label'=>(empty($id)?'Choose a file from your computer':'Update your file (optional)'),
		'note'=>(!empty($id) ? "Leave blank to keep original file":""))); ?>
	<?= $this->Form->input('title',array()); ?>
	<?= $this->Form->input('description',array('type'=>'textarea','rows'=>3)); ?>

	<script>
	$('#DownloadFile').change(function() {
		// Guess title and auto-fill if empty.
		if($('#DownloadTitle').val()) { return; }
		var filename = $('#DownloadFile').val();
		var title = filename.replace(/^.+[\\\/]/, "")
			.replace(/[.]\w+$/, "").
			underscore().
			replace(/\W+/g, "_").
			humanize().
			ucwords();
		$('#DownloadTitle').val(title).focus();
	});

	</script>
	<?= $this->Form->save('Save Download');#, array('modal'=>true)); ?>
</div>
<?php echo $this->Form->end(); ?>
</div>
