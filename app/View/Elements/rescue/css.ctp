<!-- start site design styling -->
<!--
<? #print_r($rescue) ?>
-->
<? if(empty($rescue)) { return; } ?>
<?
$theme = !empty($rescue['Rescue']['theme']) ? $rescue['Rescue']['theme'] : 'default';
$namedParams = array(); foreach($rescue['Rescue'] as $k=>$v) { 
	if(in_array($k, array('color1')) && !empty($v)) // Only color needs to go to stylesheet!
	{
		$namedParams[] = "/$k:$v"; 
	}
}
?>
<?#= $this->Less->css("themes/$theme"); # Static portion ?>
<link rel="stylesheet" type="text/css" href="/rescue/<?= $rescuename ?>/style/<?= $theme ?><?= join("",$namedParams) ?>.css"/> <!-- custom portion -->
<style>
</style>
<!-- end site design styling -->
