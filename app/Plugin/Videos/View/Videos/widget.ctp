<?= $this->Html->css("Videos.videos"); ?>
<? if(empty($updates['videos']) /*&& !$this->Html->can_edit()*/) { return; } ?>

<? $prefix = Configure::read("members_only") ? "members" : "user"; ?>
<? $pid = Configure::read("project_id"); ?>
<? $project = Configure::read("project"); ?>
<div class='widget'>
<h3>
	<?= $this->Html->link("Recent Videos", array($prefix=>1,'plugin'=>'videos','controller'=>'videos','action'=>'index')); ?>
</h3>
<? if(false && $this->Html->can_edit()) { ?>
<div class='alert-warning dashed border2'>
	<?= $this->Html->add(empty($updates['videos']) ? "Add your first video":"Add another video", array($prefix=>1,'plugin'=>'videos','controller'=>'videos','action'=>'add','project_id'=>$pid),array('title'=>'Add Video','class'=>'')); ?>
</div>
<? } ?>
<? if(!empty($updates['videos'])) { ?>
	<div class='row'>
	<? foreach($updates['videos'] as $video) { $video = $video['Video']; ?>
		<div class='paddingbottom25 col-md-6 height250'>
			<div class="Video">
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
		</div>
	<? } ?>
	</div>
	<? if(!empty($updates['videos'])) { ?>
	<div align='right'>
	<?= $this->Html->link("More videos ".$this->Html->g("chevron-right"), array('controller'=>"videos"), array('class'=>'btn more right_align medium bold')); ?>
	</div>
	<? } ?>
<? } ?>
</div>

