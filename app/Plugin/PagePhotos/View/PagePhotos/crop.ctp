<?
if(empty($modelClass)) { echo "ERROR: Unable to determine photo page settings"; return; }
# Support per-model customization in app's config file
Configure::load("pagePhoto");
$config = Configure::read("PagePhoto.$modelClass");

$scaledWidth = 300; 
if(!empty($config['scaledWidth'])) { $scaledWidth = $config['scaledWidth']; }

$placeholder = "/page_photos/images/add-a-picture.png"; 
if(!empty($config['placeholder'])) { $placeholder = $config['placeholder']; }

$photoModel = 'PagePhoto';
if(!empty($config['photoModel'])) { $photoModel= $config['photoModel']; }

$plugin = 'page_photos';
if(isset($config['plugin'])) { $plugin= $config['plugin']; } # false allowed

$controller = Inflector::pluralize(Inflector::underscore($photoModel)); # Implied from model class.
if(!empty($config['controller'])) { $controller= $config['controller']; }

$primaryKey = 'page_photo_id';
if(!empty($config['primaryKey'])) { $primaryKey= $config['primaryKey']; }

$thing = "picture";
if(!empty($config['thing'])) { $thing= $config['thing']; }

$ucThing = ucfirst($thing);

?>
<script>
$.dialogheaderhide();
</script>
<? 
$scaledWidth = 300; 
$page_photo_id = $this->Form->fieldValue("$photoModel.id");
?>
<?= $this->element("Image.js"); ?>
<div id='<?=$photoModel?>Cropper'>
<?= $this->Form->create($photoModel, array('url'=>array('plugin'=>$plugin,'controller'=>$controller,'action'=>'crop',$modelClass,$model_id),'class'=>'json')); ?>

<div class='right'>
	<button type='button' class='btn btn-warning' id='<?=$photoModel?>CropperReset'>Reset</button>
	<button type='submit' class='btn btn-primary'><span class='glyphicon glyphicon-scissors'></span> Done</button>
</div>
<h4>Drag a box to crop your picture (optional)</h4>
<div class='clear'></div>

<?
$path = $this->Form->fieldValue("$photoModel.path");
$filename = $this->Form->fieldValue("$photoModel.filename");
if($filename) { list($origWidth, $origHeight) = getimagesize(APP."/webroot/$path/$filename"); }
?>
	
	<?= $this->Form->hidden("$photoModel.id", array('value'=>$page_photo_id)); ?>
	<?= $this->Form->hidden("$photoModel.crop_x", array('id'=>"{$photoModel}CropX")); ?>
	<?= $this->Form->hidden("$photoModel.crop_y", array('id'=>"{$photoModel}CropY")); ?>
	<?= $this->Form->hidden("$photoModel.crop_w", array('id'=>"{$photoModel}CropW")); ?>
	<?= $this->Form->hidden("$photoModel.crop_h", array('id'=>"{$photoModel}CropH")); ?>

	<div style="width: <?= $scaledWidth ?>px;" class='center'>
		<?= $this->Html->image(array('plugin'=>$plugin,'controller'=>$controller,'action'=>'fullimage',$page_photo_id,$scaledWidth), array('class'=>'cropper')); ?>
	</div>

	<script>
	var cropScale = '<?= $scaledWidth / $origWidth ?>'; // 300 / 900 => 0.3333

	$('#<?=$photoModel?>CropperReset').click(function(e) {
		e.preventDefault();
		$('#<?=$photoModel?>Cropper img.cropper').cropper("clear");
	});

	$('#<?=$photoModel?>Cropper img.cropper').cropper({
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
	</script>

</div>




<?= $this->Form->end();
