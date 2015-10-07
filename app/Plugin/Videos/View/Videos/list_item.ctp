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
