<? if(empty($wh)) { $wh = ""; } ?>
<?  if(!empty($id)) { # MUST PASS id! ?>
<div class='PagePhoto padding5 <?= !empty($class) ? $class  : null ?>'>
	<? $img = $this->Html->image("/page_photos/page_photos/thumb/$id/$wh", array('class'=>'border')); ?>
	<? if(!empty($href)) { ?>
		<?= $this->Html->link($img, $href); ?>
	<? } else { ?>
		<?= $img ?>
	<? } ?>
</div>
<? } else { ?>
	<div class='alert alert-danger'>Missing 'id' for photo</div>
<? } ?>
