<?php
if(!isset($icon)) { $icon = null; }

if (!isset($class)) {
	$class = false;
}
if (!isset($close)) {
	$close = false;
}
$id = rand(10000,99999);
?>
<div id="alert<?= $id ?>" class="<?= $this->fetch("flash_class"); ?> flash_message alert<?php echo ($class) ? ' ' . $class : null; ?>">
<?php if ($close): ?>
	<a class="close" data-dismiss="alert" href="#">×</a>
<?php endif; ?>
	<? if($icon) { ?>
	<div class="block left marginright10 font24 glyphicon glyphicon-<?= $icon ?>" aria-hidden="true"></div>
	<? } ?>
	<?php echo $message; ?>
</div>
