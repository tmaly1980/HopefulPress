<? $this->assign("title", "Posts tagged '$tag'"); ?>

<div class="posts index">
<? $this->start("rightcol"); ?>
	<?= $this->requestAction("/blog/posts/recent",array('return')); ?>
<? $this->end(); ?>

	<?= $this->element("../Posts/list"); ?>


	<?= $this->element("pager"); ?>

</div>


