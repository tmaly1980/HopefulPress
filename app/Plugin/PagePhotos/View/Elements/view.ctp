<?
extract($this->PagePhoto->config(compact('parentClass','photoModel')));
if(empty($page_photo_id)) { return; } # NONE

$margin = 'margin25';

if(empty($wh)) { $wh = (!empty($width) || !empty($height)) ? "{$width}x{$height}" : ""; }

$url = array('plugin'=>$plugin,'controller'=>$controller,'action'=>'image', $page_photo_id, $wh);
if($view_hidden) { return; }
?>
<div class='PagePhoto <?= $align ?> maxwidth100p <?= $margin ?> <?= !empty($class) ? $class : "" ?>'>
	<? $img_attrs = array('class'=>"border maxwidth100p"); ?>
	<? if(!empty($width)) { $img_attrs['width'] = $width; } ?>
	<? if(!empty($height)) { $img_attrs['height'] = $height; } ?>
	<?= $this->Html->og_image(Router::url($url,true),true); # ONLY ONE ?>
	<?= $this->Html->image($url, $img_attrs); ?>
	<div id='PagePhotoCaption_<?= $page_photo_id ?>' class='center_align caption font12 maxwidth600'>
		<?= !empty($caption) ? $caption : "" ?>
	</div>
</div>
