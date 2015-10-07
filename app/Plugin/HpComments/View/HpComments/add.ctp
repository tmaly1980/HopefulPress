<div class="hpComments form">
<?= $this->Form->create("HpComment",array('url'=>array('action'=>'add'), 'json'=>1)); #array('plugin'=>'hp_comment','controller'=>'hp_comments','action'=>"add"), array('model'=>'HpComments' ?>
	<?= $this->Form->hidden("model"); ?>
	<?= $this->Form->hidden("model_id"); ?>
	<?#b= $this->Form->input("title",array('label'=>'Summary (optional)')); ?>
	<?= $this->Form->input("name",array('label'=>false,'Xlabel'=>'Comment','class'=>'width400 required', 'placeholder'=>'Your Name')); ?>
	<?= $this->Form->input("email",array('label'=>false,'Xlabel'=>'Comment','class'=>'width400','placeholder'=>'Email (optional)')); ?>
	<?= $this->Form->input("notify",array('label'=>'Notify new comments via email')); ?>
	<?= $this->Form->input("content",array('placeholder'=>'Add a comment...','label'=>false,'Xlabel'=>'Comment','class'=>'autogrow width400','rows'=>3)); ?>
	<script>
		//$('#HpCommentContent').hide_submit();
	</script>
	<?= $this->Form->submit('Add Comment',array('class'=>'waitable','Xstyle'=>'display: none;')); ?>

	<script>
		//j('.submit').css({display: 'none'});
	</script>
<?= $this->Form->end(); ?>
</div>
