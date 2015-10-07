<? $this->assign("page_title", "Question Categories"); ?>
<? if(!empty($categories)) { ?>
	<? foreach($categories as $cat) { ?>
	<p> <?= $this->Html->link($cat['Category']['title'], array('action'=>'category',$cat['Category']['id'])); ?> (<?= count($cat['Question']) ?>) </p>
	<? } ?>
	<? if(!empty($uncategorized_count)) { ?>
	<p> <?= $this->Html->link("Uncategorized", array('action'=>'category')); ?> (<?= $uncategorized_count ?>) </p>
	<? } ?>
<? } ?>

