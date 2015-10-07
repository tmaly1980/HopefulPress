<?= $this->Html->css("Videos.videos"); ?>
<? $pid = Configure::read("pid"); ?>
<? #$this->set("crumbs", true); ?>
<? $project_title = !empty($project['Project']['title']) ? $project['Project']['title'] : null ?>
<? $this->assign("page_title", "Videos".(!empty($project_title)? " For $project_title":"")); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add video", array('user'=>1,'action'=>'add','project_id'=>$pid)); ?>
<? $this->end(); ?>

<div class="videos index ">
<? /*
	<p id="VideoPage_Introduction" class='marginbottom50'>
	<? if(!empty($videoPage['VideoPage']['introduction'])) { ?>
		<?= nl2br($videoPage['VideoPage']['introduction']); ?>
	<? } ?>
	</p>
	<? if($in_admin) { ?>
	<script>
		j('#VideoPage_Introduction').inline_edit_link({before: true});
	</script>
	<? } ?>
*/ ?>
<div id="Videos" class="list">
<? if(empty($videos) && empty($video_categories)) { ?>
	<div class='nodata'>
		There are no videos yet.
		<? if($this->Html->can_edit()) { ?>
			<?= $this->Html->add("Add your first video", array('user'=>1,'action'=>'add','project_id'=>$pid)); ?>
		<? } ?>
	</div>
<? } else { ?>
	<? if(!empty($videos)) { ?>
	<div id="Videos_" class="Videos row">
		<? foreach($videos as $video) { $video = $video['Video']; $id = $video['id']; ?>
			<div class="Video col-md-3">
				<div class="VideoThumbnail">
					<? $image = $this->Html->image( !empty($video['preview_url']) ?  $video['preview_url'] : "/videos/images/blank-video.png"); ?>
					<? $play = $this->Html->image("/videos/images/play_button.png", array('class'=>'play')); ?>
					<?= $this->Html->link($image.$play, array('user'=>null,'plugin'=>'videos','controller'=>'videos','action'=>'view',$video['id'],$video['name'],'project_id'=>$video['project_id']), array('escape'=>false,'title'=>'Click to watch video')); ?>
				</div>
				<div class="">
					<?= $this->Html->link($video['title'], array('user'=>null,'plugin'=>'videos','controller'=>'videos','action'=>'view',$video['id'],$video['name'],'project_id'=>$video['project_id']),array('title'=>'Click to watch video','class'=>'title')); ?>
					<br/>
					<?= $this->Time->mondy($video['modified']) ?>
					<? /* if(!empty($video['description'])) { ?>
					<p>
						<?= $this->Html->summary($video['description']); ?>
					</p>
					<? } */ ?>
				</div>
			</div>
		<? } ?>
	</div>
	<? } ?>

<? } ?>
</div>

</div>
