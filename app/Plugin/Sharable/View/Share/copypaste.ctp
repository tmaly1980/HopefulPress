<script>$.dialogtitle('Copy/Paste this URL');</script>
<div class='view'>
	<?= $this->Form->input(false,array('id'=>'CopyPasteURL','rows'=>2,'value'=>$page_url)); ?>
</div>
<script>
$.dialogbuttons(['close']);
$('#CopyPasteURL').click(function() {
	$(this).select();
});
</script>
