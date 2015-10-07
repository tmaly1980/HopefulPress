<?= '<?xml version="1.0" standalone="no"?>' ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="<?=$width?>" height="<?=$height?>" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="gradient1" 
    	x1="0"
	y1="0"
	<? if($orient == 'vertical') { ?>
	x2="0"
	y2="100%"
	<? } else { # horizontal ?>
	x2="100%"
	y2="0"
	<? } ?>
       gradientUnits="userSpaceOnUse" 
    >
    	<? foreach($stops as $stop) { ?>
      		<stop style="stop-color:<?=isset($stop['color']) ? $stop['color']:"#000000" ?>;stop-opacity:<?=isset($stop['opacity']) ? $stop['opacity']:1 ?>;" offset="<?=isset($stop['offset']) ? $stop['offset']:'0%' ?>" />
	<? } ?>
<!--
      <stop style="stop-color:#000000;stop-opacity:1;" offset="0%" />
      <stop style="stop-color:#000000;stop-opacity:0;" offset="25%" />
      <stop style="stop-color:#000000;stop-opacity:0;" offset="75%" />
      <stop style="stop-color:#000000;stop-opacity:1;" offset="100%" />
-->
    </linearGradient>
  </defs>
    <rect
       style="fill:url(#gradient1);fill-opacity:1;stroke:none"
       width="100%"
       height="100%"
       />
</svg>
