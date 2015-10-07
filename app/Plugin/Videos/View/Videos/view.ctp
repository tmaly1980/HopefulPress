<?= $this->Html->css("Videos.videos"); ?>
<? $this->assign("page_title", $video['Video']['title']); ?>
<? if($this->Html->can_edit()) { ?>
<? $this->start("title_controls");  ?>
	<?= $this->Html->edit("Update video",array('user'=>1,'action'=>'edit', $video['Video']['id'])); ?>
<? $this->end("title_controls");  ?>
<? } ?>
<div class="Video view">
	<div class="modified"><?= $this->Time->mondy($video['Video']['modified']) ?></div>
	<br/>

	<div>
	<? if(empty($video['Video']['video_id']) || empty($video['Video']['video_site'])) { ?>
		<div class="nodata">
		No video has been added yet.
		</div>
	<? } else { ?>
		<?= $this->element("../Videos/embed", array('video'=>$video)); ?>
	<? } ?>
	</div>

	<p id='Video_description_<?= $video['Video']['id'] ?>' class="margintop25 description">
	<? if(!empty($video['Video']['description'])) { ?>
		<?= nl2br($video['Video']['description']) ?>
	<? } ?>
	</p>

</div>

