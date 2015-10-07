<? if(!$this->fetch("subscribe_container_rendered")) { ?>
<h3>Join our Mailing List</h3>
<div class='lightgreybg border padding5' id='subscribe'>
	<?= $this->element("Blog.../Subscribers/subscribe"); ?>
</div>
<? $this->assign("subscribe_container_rendered",true); } ?>
