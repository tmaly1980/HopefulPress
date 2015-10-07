<?
$w2h = 600/355;

if(empty($width)) { 
	$width = 600;
}
if(empty($height)) # Smart to keep in ratio, only need to pass width.
{
	$height = ceil( $width / $w2h );
}
?>
<? 
/*if(!empty($videoData))
{
	print_r($videoData);
}
*/
if(!empty($video))
{
	$video_site = !empty($video['Video']['video_site']) ? $video['Video']['video_site'] : null;
	$video_id = !empty($video['Video']['video_id']) ? $video['Video']['video_id'] : null;
}
$videoConfig = Configure::read("VideoConfig"); # keys have dots, so cant get all the way.
?>
<? if(!empty($video_site) && !empty($video_id)) { ?>
	<?
		$url_template = !empty($videoConfig[$video_site]['url_template']) ? ($videoConfig[$video_site]['url_template']) : null;
		$video_url = preg_replace("/%s/", $video_id, $url_template);
	?>
	<? if(!empty($video_url)) { ?>
		<iframe src="<?= $video_url ?>" width="<?= $width ?>" height="<?= $height ?>" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
	<? } else { ?>
		Unable to load video. Please try a different URL from a supported website, such as <?= $this->Text->toList(array_keys($videoConfig)) ?>.
	<? } ?>
<? } ?>
