<?
# XXX get Form->field working with view templates (not just edit forms) - ie read "thing" var.
$dummy = $this->get("dummy") ;

# Can pass pagePhoto as var.
$height = !empty($pagePhoto['height']) ? $pagePhoto['height'] : $this->Form->fieldValue("PagePhoto.height");
if(empty($width))
{
	$width = !empty($pagePhoto['width']) ? $pagePhoto['width'] : $this->Form->fieldValue("PagePhoto.width");
}
if(empty($align))
{
	$align = !empty($pagePhoto['align']) ? $pagePhoto['align'] : $this->Form->fieldValue("PagePhoto.align");
}
$caption = !empty($pagePhoto['caption'])  ? $pagePhoto['caption']  : $this->Form->fieldValue("PagePhoto.caption"); 
$view_hidden = !empty($pagePhoto['view_hidden']) ? $pagePhoto['view_hidden'] : $this->Form->fieldValue("PagePhoto.view_hidden");

if($custom_align  = $this->fetch("PagePhoto.align"))
{
	$align = $custom_align;
}

if($custom_width  = $this->fetch("PagePhoto.width"))
{
	$width = $custom_width;
}

if(!isset($margin))
{
	$margin = 'margin25';
}

if(empty($wh)) { $wh = (!empty($width) || !empty($height)) ? "{$width}x{$height}" : ""; }

if(empty($page_photo_id) && !empty($pagePhoto['id'])) { $page_photo_id = $pagePhoto['id'];  }  
if(!isset($page_photo_id))
{
	$page_photo_id = $this->Form->fieldValue("page_photo_id");
}

if(!empty($dummy))
{
	$width = "250";
}
if(empty($align)) { $align = 'right';  }
if(empty($dummy) && empty($page_photo_id)) { return; }
$url = $dummy ? "/images/stock/backgrounds/021.jpg" : array('plugin'=>'page_photos','controller'=>'page_photos','action'=>'image', $page_photo_id, $wh);
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
