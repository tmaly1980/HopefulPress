<!-- tables allow for better vertical alignment tweaks -->
<div class="header-table width100p">
<div class="header-row">
<? if($logo) { ?>
<div class='header-logo-col <?= !empty($title) || !empty($subtitle) ? "Xhidden-sm Xhidden-xs" : "" ?>'><?= $logo ?></div>
<? } ?>
<? if($title || $subtitle) { ?>
<div class='header-title-col'>
	<?= $title ?>
	<?= $subtitle ?>
</div>
<? } ?>
<? if($right || $social) { ?>
<div class='header-right-col'>
	<?= $social ?>
	<?= $right ?>
</div>
<? } ?>
</div>
</div>
