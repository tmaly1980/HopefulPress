<div class="posts padding10">
	<h3>Recent Blog Posts</h3>
	<? foreach($posts as $post) { ?>
	<div class='paddingtop15'>
		<div class=''>
			<?= $this->Html->link($post['Post']['title'], "http://blog.{$default_domain}".Router::url(array('action'=>'view',$post['Post']['idurl'])), array('class'=>'color')); ?>
		</div>
		<i><?= $this->Time->mondy($post['Post']['created']); ?></i>
	</div>
	<? } ?>

	<div align='right'>
		<?= $this->Html->link("More...", "http://blog.$default_domain".Router::url(array('action'=>'index')), array('class'=>'color green')); ?>
	</div>

</div>

