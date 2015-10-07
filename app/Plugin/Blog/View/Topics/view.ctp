<? $this->set("title", $topic['Topic']['title']); ?>
<? $id = $topic['Topic']['id']; ?>

<div class="topics view">
<? $this->start("rightcol"); ?>
	<?= $this->Share->share(); ?>

	<?#= $this->requestAction("/blog/topics/related/$id",array('return')); ?>

	<?#= $this->requestAction("/blog/topics/recent",array('return')); ?>
<? $this->end(); ?>

<?= $this->element("../Topics/item", array('topic'=>$topic)); ?>

</div>
