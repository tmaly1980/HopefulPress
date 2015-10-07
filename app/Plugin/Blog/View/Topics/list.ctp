<? foreach($topics as $topic) { ?>
	<?= $this->element("../Topics/item", array('topic'=>$topic)); ?>
<? } ?>
