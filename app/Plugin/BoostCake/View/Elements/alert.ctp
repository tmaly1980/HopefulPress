<?php
if(!isset($icon)) { $icon = null; }

if (!isset($class)) {
	$class = false;
}
if (!isset($close)) {
	$close = false;
}
?>
<div class="alert<?php echo ($class) ? ' ' . $class : null; ?>">
<?php if ($close): ?>
	<a class="close" data-dismiss="alert" href="#">×</a>
<?php endif; ?>
	<? if($icon) { ?>
	<span class="glyphicon glyphicon-<?= $icon ?>" aria-hidden="true"></span>
	<? } ?>
	<?php echo $message; ?>
</div>
