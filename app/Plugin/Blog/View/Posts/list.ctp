<? if(empty($posts)) { ?>
	<div class='nodata large'>
		No posts.
	</div>
<? } else { ?>
	<? foreach($posts as $post) { $url = array('action'=>'view',$post['Post']['idurl']); 
	
	
	?>
<div class='margintop10 padding10'>
	<div class='large marginbottom5'> <?= $this->Html->link($post['Post']['title'], $url, array('class'=>'color bold')); ?> 
		<? if(!empty($post['Post']['draft'])) { ?>
		<b> - Draft</b>
		<? } ?>
	</div>
	<div class='italic'><?= $this->Time->mondy($post['Post']['created']); ?></div>

	<div class='paddingtop15 double'>
		<?= $this->Text->truncate($post['Post']['content'], 500); ?>
		<?= $this->Html->link("[read more...]", $url, array('class'=>'color red')); ?>
	</div>
	<div id='CommentCount_<?= $post['Post']['id'] ?>' class='CommentCount margintop25'></div>
	<? if(Configure::read("prod")) { ?>
		<script>Facebook.commentsCount("#CommentCount_<?= $post['Post']['id'] ?>", "<?= $this->Html->url($url, true); ?>");</script>
	<? } ?>
</div>
	<? } ?>

<? } ?>
