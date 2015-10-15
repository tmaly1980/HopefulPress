<!-- start site design styling -->
<!--
<? #print_r($rescue) ?>
-->
<? if(empty($rescue)) { return; } ?>
<?
$theme = !empty($rescue['Rescue']['theme']) ? $rescue['Rescue']['theme'] : 'default';
$color1 = !empty($rescue['Rescue']['color1']) ? $rescue['Rescue']['color1'] : null;

$url = array('plugin'=>null,'controller'=>'rescues','action'=>'style',$theme, 'rescue'=>$rescuename,'ext'=>'css');
if(empty($color1)) { $url['color1'] = $color1; }
?>
<?#= $this->Less->css("themes/$theme"); # Static portion ?>
<link rel="stylesheet" type="text/css" href="<?=Router::url($url); ?>"/>
<style>
</style>
<!-- end site design styling -->
