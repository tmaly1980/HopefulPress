<? extract($config = $this->PagePhoto->config(compact('parentClass','photoModel'))); ?>
<? error_log("CFG=".print_r($config,true));  ?>
<script>
$.dialogheaderhide();
</script>
<? 
$scaledWidth = 300; 
?>
<?= $this->element("Image.js"); ?>
<div id='<?=$photoModel?>Cropper'>
<?= $this->Form->create($formModel, array('url'=>array('plugin'=>$plugin,'controller'=>$controller,'action'=>'crop',$parentClass,$photoModel,$model_id),'class'=>'json')); ?>

<? $page_photo_id = $this->Form->fieldValue("id"); ?>

<div class='right'>
	<button type='button' class='btn btn-danger' id='<?=$photoModel?>CropperCancel'><span class='glyphicon glyphicon-remove'></span> Cancel</button>
	<button type='button' class='btn btn-warning' id='<?=$photoModel?>CropperReset'><span class='glyphicon glyphicon-refresh'></span> Reset</button>
	<button type='submit' class='btn btn-primary'><span class='glyphicon glyphicon-scissors'></span> Done</button>
</div>
<h4>Drag a box to crop your picture (optional)</h4>
<div class='clear'></div>

<?
$path = $this->Form->fieldValue("$photoModel.path");
$filename = $this->Form->fieldValue("$photoModel.filename");
if($filename) { list($origWidth, $origHeight) = getimagesize(APP."/webroot/$path/$filename"); }
?>
	
	<?= $this->Form->hidden("id", array('value'=>$page_photo_id)); ?>
	<?= $this->Form->hidden("crop_x", array('id'=>"{$photoModel}CropX")); ?>
	<?= $this->Form->hidden("crop_y", array('id'=>"{$photoModel}CropY")); ?>
	<?= $this->Form->hidden("crop_w", array('id'=>"{$photoModel}CropW")); ?>
	<?= $this->Form->hidden("crop_h", array('id'=>"{$photoModel}CropH")); ?>

	<div style="width: <?= $scaledWidth ?>px;" class='center padding25'>
		<?= $this->Html->image(array('plugin'=>$plugin,'controller'=>$controller,'action'=>'fullimage',$page_photo_id,$scaledWidth), array('class'=>'cropper')); ?>
	</div>

	<script>
	var cropScale = '<?= $scaledWidth / $origWidth ?>'; // 300 / 900 => 0.3333

	$('#<?=$photoModel?>CropperCancel').click(function(e) {
		e.preventDefault();
		$.dialogclose();
	});

	$('#<?=$photoModel?>CropperReset').click(function(e) {
		e.preventDefault();
		$('#<?=$photoModel?>Cropper img.cropper').cropper("clear");
	});

$('#<?=$photoModel?>Cropper img.cropper').load(function() {
	//alert('loaded shifted down stupidly');
	$(this).cropper({
		//aspectRatio: 16 / 9,
		data: { // x,y,width,height; current values, scaled down.
			//x: 10,y:10,width:100,height: 100
			/*
			x: '<?= $this->Form->fieldValue("PagePhoto.cropX"); ?>',
			y: '<?= $this->Form->fieldValue("PagePhoto.cropY"); ?>',
			width: '<?= $this->Form->fieldValue("PagePhoto.cropW"); ?>',
			height: '<?= $this->Form->fieldValue("PagePhoto.cropH"); ?>'
			*/
		},
		//preview: ".img-preview", // thumb preview
		built: function() {
			$(this).cropper("clear");
		},
		done: function(data) { // x,y,width,height
			// set form values (or submit form)
			$('#<?=$photoModel?>CropX').val( Math.round(data.x / cropScale) );
			$('#<?=$photoModel?>CropY').val( Math.round(data.y / cropScale) );
			$('#<?=$photoModel?>CropW').val( Math.round(data.width / cropScale) );
			$('#<?=$photoModel?>CropH').val( Math.round(data.height / cropScale) );
		}
	});
});
	</script>

	<div class='clear'></div>
</div>




<?= $this->Form->end();
