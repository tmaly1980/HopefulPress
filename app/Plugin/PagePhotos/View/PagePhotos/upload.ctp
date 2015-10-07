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

if(empty($modelClass)) { 
	$modelClass = $this->Form->defaultModel;
	if(empty($modelClass) || $modelClass == $photoModel)
	{
		echo "No page information passed"; return false; 
	}
}
if(empty($model_id)) { $model_id = null; }
?>
<script>$.dialogtitle("Add a <?=$thing?> from your computer");</script>
<div class='form <?=$photoModel?>Upload'>
<?= $this->Form->create($photoModel, array('url'=>array('plugin'=>$plugin,'controller'=>$controller,'action'=>'upload',$modelClass,$model_id), 'type'=>'file','class'=>'json','id'=>"{$photoModel}UploadForm")); ?>

	<?= $this->Form->hidden("$photoModel.width", array('value'=>'300')); ?>

	<?= $this->Form->input("$photoModel.file", array('label'=>'Choose a picture from your computer','type'=>'file','onChange'=>"$('#{$photoModel}UploadForm').submit();")); ?>

	<?#= $this->Form->save("Upload", array('modal'=>true)); ?>
	<br/>
	<script>
	//$.dialogbuttons(['cancel','save']);
	</script>
<?= $this->Form->end(); ?>
</div>
