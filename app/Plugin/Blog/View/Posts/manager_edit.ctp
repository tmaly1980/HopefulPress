<?= $this->element("Core.js/editor"); ?>

<? $id = !empty($this->data['Post']['id']) ? $this->data['Post']['id'] : null; ?>
<?= $this->assign("page_title", !empty($id) ? "Edit Blog Post" : "Add Blog Post"); ?>
<div class="blogPosts form width600">
<?php echo $this->Form->create('Post'); ?>
		<?= $this->Form->input('id'); ?>
		<?= $this->Form->title(); ?>
		<?= $this->Slug->slug('url', array('prefix'=>'post')); ?>
		<?= $this->Form->input("draft"); ?>

		<?= $this->element("PagePhotos.edit"); ?>
		<? /*
		<?#= $this->Form->input("created", array('type'=>'text','label'=>'Publication Date','datepicker'=>1,'default'=>date('M j, Y'))); ?>

		<?#= $this->Form->input("nav_topic", array('options'=>$navTopics,'empty'=>'[None]')); ?>
		<?#= $this->Form->input("blog_topic_id", array('type'=>'hidden')); ?>
		<?#= $this->Form->input("parent_id", array('type'=>'hidden')); ?>

		<?
		$locations = array();
		foreach($topics as $topic)
		{
			$tid = $topic['Topic']['id'];
			$ttitle = $topic['Topic']['title'];
			$locations["$tid"] = $ttitle;
			foreach($topic['postlist'] as $ppid=>$ptitle)
			{
				$locations["$tid:$ppid"] = $ptitle;
			}
		}
		$default_location = 
			(!empty($this->data['Post']['blog_topic_id']) ? $this->data['Post']['blog_topic_id']:"") . ":" . 
			(!empty($this->data['Post']['blog_parent_id']) ? $this->data['Post']['blog_parent_id']:"");
		?>
		<?= $this->Form->input("location", array('options'=>$locations,'value'=>$default_location)); ?>
		<script>
		j('#PostLocation').change(function() {
			var val = j(this).val().split(":");
			j('#PostBlogTopicId').val(val[0]);
			j('#PostParentId').val(val.length > 1 ? val[1] : '');
		});
		j(document).ready(function() {
			j('#PostLocation').change(); // load hidden fields... in case new add
		});
		</script>

		<?#= $this->Form->summary(); ?>

		<div class='ui-widget'>
			<?  $tags = Set::extract("/Tag/name", !empty($this->data['Tag']) ? $this->data : array()); ?>
			<?= $this->Form->input("Tag.tags", array('id'=>'tags','type'=>'text','label'=>'Tags','class'=>'width95p','value'=>join(", ", $tags))); ?>
		</div>
		*/ ?>


		<?= $this->Form->content(); ?>

	<?= $this->Form->save(); ?>
<?php echo $this->Form->end(); ?>
</div>
<script>
(function($) { 
	// CCC
	var availableTags = <?= $this->Js->object($taglist); ?>;

 function split( val ) {
 	return val.split( /,\s*/ );
 }

 function extractLast( term ) {
 	return split( term ).pop();
 }

 $( "#tags" )
 // don't navigate away from the field on tab when selecting an item
 	.bind( "keydown", function( event ) {
 		if ( event.keyCode === $.ui.keyCode.TAB &&
 		$( this ).data( "autocomplete" ).menu.active ) {
 			event.preventDefault();
 		}
 	})
 	.autocomplete({
 		minLength: 0,
 		source: function( request, response ) {
 			// delegate back to autocomplete, but extract the last term
 			response( $.ui.autocomplete.filter(
 			availableTags, extractLast( request.term ) ) );
 		},
 		focus: function() {
 			// prevent value inserted on focus
 			return false;
 		},
 		select: function( event, ui ) {
 			var terms = split( this.value );
 			// remove the current input
 			terms.pop();
 			// add the selected item
 			terms.push( ui.item.value );
 			// add placeholder to get the comma-and-space at the end
 			terms.push( "" );
 			this.value = terms.join( ", " );
 			return false;
 		}
 	});
})(jQuery);

</script>
