<? $this->assign("page_title", 'About Page'); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->blink("back", "All Pages", array("action"=>"index")); ?>
<? $this->end(); ?>

<div class="pages form">
	ABOUT PAGE (or home or contact) FORM.... maybe inline edit is best?
	- or maybe whole page with inline edit enabled for all fields and a single submit?
	- or maybe a message/note for each block if logged in
</div>
