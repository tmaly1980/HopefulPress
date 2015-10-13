<?= $this->element("Editable.js"); ?>
<?
# ModelClass etc is the parent object. Might be passed optional photo object/class, ie Rescue.AboutPhoto
#
# DEFAULTS.
$parentClass = $this->Form->defaultModel;
$photoModel = 'PagePhoto';
$pluginModelClass = $parentClass;

if(!empty($modelClass))  # Explicit, on photo model and maybe parent too.
{
	list($prefix,$photoModel) = pluginSplit($modelClass);
	if(!empty($prefix))
	{
		$parentClass = $prefix;
		$pluginModelClass = $modelClass; # Rescue.Logo
	}
}
$photoID = $photoModel; # Dont set any later since can be overwritten/misled by config...
# IF WE DONT WANT PAGEPHOTO, BE EXPLICIT

# Support per-parent customization in app's config file; sharing PagePhoto  (no need for custom model)
Configure::load("pagePhoto");
$config = Configure::read("PagePhoto.$pluginModelClass");

#echo "LOADING=$pluginModelClass";

# $photoModel = RescueLogo

$scaledWidth = 300; 
if(!empty($config['scaledWidth'])) { $scaledWidth = $config['scaledWidth']; }

$placeholder = "/page_photos/images/add-a-picture.png"; 
if(!empty($config['placeholder'])) { $placeholder = $config['placeholder']; }

if(!empty($config['photoModel'])) { $photoModel= $config['photoModel']; }

#echo "MC=$modelClass, PM=$photoModel, PID=$photoID";

$plugin = ($photoModel == 'PagePhoto' ? 'page_photos' : null);
if(isset($config['plugin'])) { $plugin= $config['plugin']; } # false allowed

$controller = Inflector::pluralize(Inflector::underscore($photoModel)); # Implied from model class.
if(!empty($config['controller'])) { $controller= $config['controller']; }

# ALWAYS get key name from model relation....
ClassRegistry::init($parentClass);
$parent = new $parentClass();
$primaryKey = !empty($parent->belongsTo[$photoID]['foreignKey']) ?
	$parent->belongsTo[$photoID]['foreignKey'] : 
	Inflector::underscore($photoID)."_id"; # Logo => logo_id
if(!empty($config['primaryKey'])) { $primaryKey= $config['primaryKey']; }

$thing = "picture";
if(!empty($config['thing'])) { $thing= $config['thing']; }

$btnSize = "btn-xs";
if(!empty($config['btnSize'])) { $btnSize= $config['btnSize']; }

$onEditLoad = "";
if(!empty($config['onEditLoad'])) { $onEditLoad= $config['onEditLoad']; }


$ucThing = ucfirst($thing);

# Separate params allows for passing from page?


if(empty($page_photo_id)) { # Could be set after save.
	$page_photo_id = $this->Form->fieldValue($primaryKey);
}

#echo "PK=$primaryKey, PPID=$page_photo_id";

if(empty($height)) {
	$height = $this->Form->fieldValue("$photoModel.height");
}
if(empty($width)) {
	$width = $this->Form->fieldValue("$photoModel.width");
}

if(empty($class)) { $class = ''; }


if(empty($model_id)) # model_id is the parent's ID! We know how to replace
{
	#$model_id = $this->Model->inline() ? $this->Model->id() : null; # If inline edit, change record when saved. Otherwise, leave up to form we're supposedly in.
	$model_id = $this->Form->id() ; # If inline edit, change record when saved. Otherwise, leave up to form we're supposedly in.
}
$align = $this->Form->fieldValue("$photoModel.align"); 
if(empty($align)) { $align = 'right'; }

$data = $this->Form->data();

#error_log("LOST PIC: PPID=$page_photo_id, PM=$photoModel,DATA=".print_r($data,true));

#echo "PPID=$page_photo_id, PM=$photoModel, D=".print_r($data[$photoModel],true);

?>
<div id="<?= $photoID ?>" class="<?= !empty($div) ? (!empty($div['class']) ? $div['class'] : $div) : null; ?> relative <?#= $align ?> PagePhoto center_align ">
	<? if(empty($page_photo_id) || empty($data[$photoModel])) { ?>
		<?= $this->Html->link($this->Html->image($placeholder,array('class'=>'border')), array('plugin'=>$plugin,'controller'=>$controller,'action'=>'upload',$pluginModelClass), array('class'=>'dialog','title'=>"Add $ucThing"));?>
	<? } else { ?>
	<?
	if(empty($data[$photoModel])) {
		echo "<b class='red'>PARENT OBJECT NEEDS belongsTo $photoModel</b>";
		return;
	}
	?>
		<?= $this->Form->hidden("$photoID.id", array('value'=>$page_photo_id)); ?>

		<div class='absolute top0 right0' align='right'>
				<?= $this->Html->blink("camera", null, array('plugin'=>$plugin,'controller'=>$controller,'action'=>'upload',$pluginModelClass,$page_photo_id), array('class'=>"white btn-primary dialog $btnSize",'title'=>"Update $ucThing")); ?>
				<?= $this->Html->blink("scissors", null, array('plugin'=>$plugin,'controller'=>$controller,'action'=>'crop',$pluginModelClass,$page_photo_id), array('class'=>"white dialog btn btn-success $btnSize",'title'=>'Re-crop picture','data-form'=>true,'data-header'=>'0')); ?>
				<?= $this->Html->blink("trash", null, array('plugin'=>$plugin,'controller'=>$controller,'action'=>'delete',$pluginModelClass,$page_photo_id), array('class'=>"white btn-danger json $btnSize",'title'=>"Remove $ucThing",'confirm'=>"Are you sure you want to remove this $thing?",'data-replace'=>$photoID)); ?>
		</div>
		<div class='clear'></div>
		<?= $this->Form->hidden("$parentClass.$primaryKey", array('value'=>$page_photo_id)); ?>
		<? $img_attrs = array('id'=>"{$photoModel}_$page_photo_id", 'class'=>"maxwidth100p margin5 $class"); ?>
		<? if(!empty($width)) { $img_attrs['width'] = $width; } ?>
		<? if(!empty($height)) { $img_attrs['height'] = $height; } ?>
		<?= $this->Html->image(array('plugin'=>$plugin,'controller'=>$controller,'action'=>'image',$page_photo_id,$scaledWidth,'?'=>array('rand'=>rand(10000,50000))),$img_attrs); ?>

		<div class='clear'></div>

		<script>
			// Don't hide controls until photo successfully loads - so know how to clear.
			$('#<?=$photoModel?>_<?= $page_photo_id ?>').load(function() {
				$(this).closest('.PagePhoto').find('.absolute').addClass('controls');
			});
		</script>


		<? if(empty($config['nocaption'])) { ?>
		<div id='<?=$photoModel?>_Caption_<?= $page_photo_id ?>' class='caption font12 center_align'>
			<?= $this->Form->fieldValue("$photoModel.caption"); ?>
		</div>
		<script>

			$('#<?=$photoModel?>_Caption_<?= $page_photo_id ?>').inline_edit({link: "Add caption/Edit caption", type: 'textarea', rows: 4, plugin: 'page_photos'});
		</script>
		<? } ?>
	<? } ?>
	<!-- if passed ID, then save record to contain the picture when uploaded. ie for about_pages inline edit; otherwise, up to existing page to save hidden field. -->
</div>
<div class='clear'></div>
<? if(!empty($onEditLoad)) { ?>
<script>
	<?= $onEditLoad ?>
</script>
<? } ?>
