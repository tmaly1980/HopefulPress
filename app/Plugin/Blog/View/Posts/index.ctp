<?= $this->element("Www.sidebar"); ?>
<? #$this->assign("page_title", "Building better relationships and communities"); ?>

<div class="posts index">

	<? if(!empty($tag)) { ?>
	<p>
		Posts tagged with '<?= $tag ?>'
	</p>
	<? } ?>

	<?= $this->element("../Posts/list"); ?>


	<?= $this->element("pager"); ?>

</div>

