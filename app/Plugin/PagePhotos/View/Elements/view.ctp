<?
extract($this->PagePhoto->config(compact('parentClass','photoModel','width','height','align','margin','class')));
if(empty($page_photo_id)) { return; } # NONE

if(empty($wh)) { $wh = (!empty($width) || !empty($height)) ? "{$width}x{$height}" : ""; }

$url = array('plugin'=>$plugin,'controller'=>$controller,'action'=>'image', $page_photo_id, $wh);
$large_url = array('plugin'=>$plugin,'controller'=>$controller,'action'=>'original', $page_photo_id);
if($view_hidden) { return; }
?>
<div class='PagePhoto <?= $align ?> maxwidth100p <?= $margin ?> <?= !empty($class) ? $class : "" ?>'>
	<? $img_attrs = array('class'=>"border maxwidth100p"); ?>
	<? if(!empty($width)) { $img_attrs['width'] = $width; } ?>
	<? if(!empty($height)) { $img_attrs['height'] = $height; } ?>
	<?= $this->Html->og_image(Router::url($url,true),true); # ONLY ONE ?>
	<? $image = $this->Html->image($url, $img_attrs); ?>
	<?= !empty($lightbox) ? $this->Html->link($image,$large_url,array('class'=>'lightbox','title'=>$caption)) : $image; ?>
	<? if(!empty($download)) { ?>
	<div class='center_align'>
		<?= $this->Html->link($this->Html->g("download"). " Download", $large_url); ?>
	</div>
	<? } ?>
	<div id='PagePhotoCaption_<?= $page_photo_id ?>' class='center_align caption font12 maxwidth600'>
		<?= !empty($caption) ? $caption : "" ?>
	</div>
</div>
