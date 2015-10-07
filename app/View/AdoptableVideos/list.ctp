<div id="AdoptableVideos" class="row">
<? if(empty($adoptable["Video"])) { ?>
	<div class='col-md-12 nodata'>
		No videos available
	</div>
<? } else { ?>
	<? foreach($adoptable["Video"] as $video) { ?>
	<div class='Video col-md-6'>
		<?= $this->element("Videos.../Videos/embed", array('video'=>array('Video'=>$video,'width'=>"300"))); ?>
	</div>
	<? } ?>
<? } ?>
</div>
