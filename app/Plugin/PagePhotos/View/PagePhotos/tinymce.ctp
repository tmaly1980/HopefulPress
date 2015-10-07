<? $this->layout = 'ajax'; ?>
<script>
	//window.parent.window.
	jbImagesDialog.uploadFinish({
		filename:'<?php echo $file_name; ?>',
		result: '<?php echo $result; ?>',
		resultCode: '<?php echo $resultcode; ?>'
	});
</script>
