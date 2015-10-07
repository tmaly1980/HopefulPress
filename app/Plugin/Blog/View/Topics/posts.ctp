<? if(!empty($posts)) { # Should be in a hierarchy... ?>
	<? foreach($posts as $post) { # parent post ?>
	<?= $this->Html->link($post['title'], array('controller'=>'posts','action'=>'view',$post['idurl'])); ?>
		<? if(!empty($post['Subposts'])) { ?>
		<ul>
			<? foreach($post['Subposts'] as $subpost) { ?>
			<li>
				<?= $this->Html->link($subpost['title'], array('controller'=>'posts','action'=>'view',$subpost['idurl'])); ?>
			</li>
			<? } ?>
		</ul>
		<? } ?>
	<? } ?>
<? } ?>
