<?
$title = $current_site['Site']['title']; 
if($design_title = $siteDesign['SiteDesign']['title']) { $title = $design_title; }
$logo_id = $siteDesign['SiteDesign']['site_design_logo_id']; 

if(!empty($siteDesign)  && empty($design_title) && !empty($logo_id)) { return; } 
# Logo set and title cleared
?>
<? if(!isset($themeSettings['header-brand']) || !empty($themeSettings['header-brand'])) { ?>
<div class='header-title'>
	<h1>
		<?= $this->Html->link($title, "/"); ?>
	</h1>
</div>
<? } ?>
