<?
$logo_id = $siteDesign['SiteDesign']['site_design_logo_id']; 
$size = !empty($siteDesign["SiteDesign"]['logo_size']) ? $siteDesign["SiteDesign"]['logo_size'] : 175;
if(!empty($logo_id)) { ?>
	<div class='header-logo left'>
		<?= $this->Html->og_image(Router::url("/site_design_logos/image/$logo_id",true)); ?>
		<?= $this->Html->link($this->Html->image("/site_design_logos/image/$logo_id/x$size"), "/", array('class'=>'')); ?>
	</div>

<? } ?>
