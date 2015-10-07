	<div class='header-subtitle'>
	<? if(!empty($memberPage)) { ?>
		<div class=''>
			<?= $this->Html->link("Members Only", "/members", array('class'=>'font2em')); ?>
		</div>
	<? } else if(!empty($siteDesign['SiteDesign']['subtitle'])) { ?>
		<? if(!isset($themeSettings['subtitle']) || !empty($themeSettings['subtitle'])) { ?>
			<div class='subtitle'><?= $siteDesign['SiteDesign']['subtitle'] ?></div>
		<? } ?>
	<? } ?>
	</div>

