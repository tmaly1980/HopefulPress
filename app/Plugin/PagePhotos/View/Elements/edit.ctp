<?= $this->element("Editable.js"); ?>
<?
# ModelClass etc is the parent object. Might be passed optional photo object/class, ie Rescue.AboutPhoto
#
# DEFAULTS.

extract($this->PagePhoto->config(compact('parentClass','photoModel')));

?>
<div id="<?= $photoAlias ?>" class="<?= !empty($div) ? (!empty($div['class']) ? $div['class'] : $div) : null; ?> relative <?#= $align ?> PagePhoto center_align ">
	<? if(empty($page_photo_id) || empty($data[$photoModel])) { ?>
		<?= $this->Html->link($this->Html->image($placeholder,array('class'=>'border')), array('plugin'=>$plugin,'controller'=>$controller,'action'=>'upload',$parentClass,$photoAlias), array('class'=>'dialog','title'=>"Add $ucThing"));?>
	<? } else { ?>
	<?
	if(empty($data[$photoModel])) {
		echo "<b class='red'>PARENT OBJECT NEEDS belongsTo $photoModel</b>";
		return;
	}
	?>
		<?= $this->Form->hidden("$photoAlias.id", array('value'=>$page_photo_id)); ?>

		<div class='absolute top0 right0' align='right'>
				<?= $this->Html->blink("camera", null, array('plugin'=>$plugin,'controller'=>$controller,'action'=>'upload',$parentClass,$photoModel,$page_photo_id), array('class'=>"white btn-primary dialog $btnSize",'title'=>"Update $ucThing")); ?>
				<?= $this->Html->blink("scissors", null, array('plugin'=>$plugin,'controller'=>$controller,'action'=>'crop',$parentClass,$photoModel,$page_photo_id), array('class'=>"white dialog btn btn-success $btnSize",'title'=>'Re-crop picture','data-form'=>true,'data-header'=>'0')); ?>
				<?= $this->Html->blink("trash", null, array('plugin'=>$plugin,'controller'=>$controller,'action'=>'delete',$parentClass,$photoModel,$page_photo_id), array('class'=>"white btn-danger json $btnSize",'title'=>"Remove $ucThing",'confirm'=>"Are you sure you want to remove this $thing?",'data-replace'=>$photoAlias)); ?>
		</div>
		<div class='clear'></div>
		<?= $this->Form->hidden("$parentClass.$primaryKey", array('value'=>$page_photo_id)); ?>
		<? $img_attrs = array('id'=>"{$photoModel}_$page_photo_id", 'class'=>"maxwidth100p margin5 $class"); ?>
		<? if(!empty($width)) { $img_attrs['width'] = $width; } ?>
		<? if(!empty($height)) { $img_attrs['height'] = $height; } ?>
		<?= $this->Html->image(array('prefix'=>false,'plugin'=>$plugin,'controller'=>$controller,'action'=>'image',$page_photo_id,$scaledWidth,'?'=>array('rand'=>rand(10000,50000))),$img_attrs); ?>

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
