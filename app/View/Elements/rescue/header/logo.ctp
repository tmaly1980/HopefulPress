<?
$logo_id = $rescue['Rescue']['rescue_logo_id']; 
$size = 175;#!empty($siteDesign["SiteDesign"]['logo_size']) ? $siteDesign["SiteDesign"]['logo_size'] : 175;
if(!empty($logo_id)) { ?>
	<div class='header-logo left'>
		<?= $this->Html->og_image(Router::url("/rescue_logos/image/$logo_id",true)); ?>
		<?= $this->Html->link($this->Html->image("/rescue_logos/image/$logo_id/x$size"), array('plugin'=>null,'prefix'=>null,'controller'=>'rescues','action'=>'view','rescue'=>$rescuename), array('class'=>'')); ?>
	</div>

<? } ?>
