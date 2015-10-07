<? $this->assign("page_title", "Add a Video"); ?>
<? $models = array_keys($this->request->models); $modelClass = $models[0]; ?>
<div class="form" style="width: 700px;">
<?= $this->Form->create($modelClass, array('url'=>array('action'=>'details'))); ?>
	<?= $this->Form->hidden('project_id'); ?>
	<? if(!empty($parent_key)) { ?>
		<?= $this->Form->hidden($parent_key); ?>
	<? } ?>
	<?= $this->Form->input("video_url",array(
		'id'=>'VideoUrl',
		'label'=>'Video webpage location (URL)',
		'style'=>'width: 94%','class'=>'font12',
		)); ?>
	<?= $this->Form->submit('Preview',array('id'=>'Preview','class'=>'waitable replace right')); ?>

	<div id="video_details">
	</div>
<?= $this->Form->end(); ?>
<script>
$('#Preview').click(function() {
	$('#video_details').videoQuery($('#VideoUrl').val(), {
	});

});

var apikeys = 
{
	"youtube.com": "",
};

var apiurls = 
{
	"youtube.com": "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=",
};

$.fn.videoQuery = function(url, opts)
{
	

};

</script>

<? if(true || empty($this->data[$modelClass]['video_url'])) { # Hide unless really needed. Must be full thing, else page_id will screw up ?>
	<div style="padding: 10px;">
	
	
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
<?  } ?>


<textarea style="display: none; width: 100%;" rows=10 id="raw"></textarea>

<div id="video_details">
	<?= $this->element("Videos.../Videos/user_details"); ?>
</div>

</div>

</div>
<script>
</script>
