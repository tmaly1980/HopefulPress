<?  if(!empty($id)) { # MUST PASS page_photo_id! ?>
<div style='margin: 5px; float: right;'>
	<?= $this->Html->image("/page_photos/thumb/$id", array('style'=>'border: solid #CCC 1px;')); ?>
</div>
<? } ?>
