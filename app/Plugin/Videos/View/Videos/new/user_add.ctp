<div class="form border">
<div style="padding: 10px;">
<?= $this->Form->create($modelClass, array('url'=>array('action'=>'details'))); ?>
	<?= $this->Form->hidden('project_id'); ?>
	<? if(!empty($parent_key)) { ?>
		<?= $this->Form->hidden($parent_key); ?>
	<? } ?>

	<?#= $this->Form->hidden("id"); ?>
	<?#= $this->Form->hidden("video_page_id",array('value'=>$video_page_id)); ?>
<div class="">
	<?= $this->Form->input("video_url",array(
		'label'=>'Video webpage location (URL)',
		'style'=>'width: 94%','class'=>'font12',
		)); ?>
	<?= $this->Form->submit('Preview',array('class'=>'waitable replace right')); ?>

	<div class="">
		Looks like:<br/><i>http://youtube.com/watch?v=VIDEO_ID</i><br/> <i>http://youtube.com/v/VIDEO_ID</i><br/> <i>http://vimeo.com/VIDEO_NUMBER</i>
	</div>
	<div class="clear"></div>

</div>
<?= $this->Form->end(); ?>
</div>

<div class="font14 margin_top">
<p>To upload a new video from your computer, first post the video on
	<?= $this->Html->link("YouTube.com", "http://upload.youtube.com/my_videos_upload",array('target'=>'_blank','class'=>'color')); ?> (commercial) or
	<?= $this->Html->link("Vimeo.com", "http://vimeo.com",array('target'=>'_blank','class'=>'color')); ?> (non-commercial only). 
</div>

</div>
