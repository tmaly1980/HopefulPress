<? if(!empty($post['Tag'])) { ?>
<div class='padding10'>
	<h3>Tags</h3>
	<div>
		<? foreach($post['Tag'] as $tag) { ?>
			<?= $this->Html->link($tag['name'], array('action'=>'tags',$tag['name']), array()); ?>
			&nbsp;
		<? } ?>
	</div>
</div>
<? } ?>
