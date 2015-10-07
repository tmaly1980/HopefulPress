<? $this->assign("title", "Building better relationships and communities"); ?>

<div class="topics index">
<? $this->start("rightcol"); ?>
	<?= $this->requestAction("/blog/posts/recent",array('return')); ?>
<? $this->end(); ?>

	<?= $this->element("../Topics/list"); ?>

	<?= $this->element("pager"); ?>

</div>

