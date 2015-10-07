<? if(!empty($posts)) { ?>
<div class="posts lightgreybg padding25">
	<h4>Related Posts</h4>
	<? foreach($posts as $post) { ?>
	<div class='paddingtop15'>
		<div class=''>
			<?= $this->Html->link($post['Post']['title'], "http://blog.{$default_domain}".Router::url(array('action'=>'view',$post['Post']['idurl'])), array('class'=>'color')); ?>
		</div>
		<i><?= $this->Date->mondy($post['Post']['created']); ?></i>
	</div>
	<? } ?>
</div>
<? } ?>
