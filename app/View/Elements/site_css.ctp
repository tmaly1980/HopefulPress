<!-- start site design styling -->
<!--
<? print_r($siteDesign) ?>
-->
<? if(empty($siteDesign)) { return; } ?>
<?
$theme = !empty($siteDesign['SiteDesign']['theme']) ? $siteDesign['SiteDesign']['theme'] : 'default';
$namedParams = array(); foreach($siteDesign['SiteDesign'] as $k=>$v) { 
	if(in_array($k, array('color1')) && !empty($v)) // Only color needs to go to stylesheet!
	{
		$namedParams[] = "/$k:$v"; 
	}
}
?>
<?#= $this->Less->css("themes/$theme"); # Static portion ?>
<link rel="stylesheet" type="text/css" href="/site_designs/style/<?= $theme ?><?= join("",$namedParams) ?>.css"/> <!-- custom portion -->
<style>
</style>
<!-- end site design styling -->
