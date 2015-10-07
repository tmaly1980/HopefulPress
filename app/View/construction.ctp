<? $this->layout = 'plain'; ?>
<? $this->start("before_maincol"); ?>
<?= $this->element("admin/login"); ?>
<? $this->end("before_maincol"); ?>
<? $this->assign("page_title", "Website Under Construction"); ?>
<? $this->set("content_class", "width550"); ?>

<div class='medium bold center_align'>
	<?= $this->Html->g("hourglass font48"); ?>
	<p class='padding25'>This site is not currently available. Please come back later.</p>
	<? if($this->Html->is_admin()) { ?>
		<?= $this->Html->edit("Re-enable your website","/admin/billing"); ?>
	<? } ?>
<div class='clear'></div>
</div>

