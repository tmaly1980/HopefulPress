<? if(!empty($hostname)) { # Currently on a site. ?>
<? if(empty($preview_wrapper)) { ?>
<!-- start main -->
<?= $this->element("defs"); ?>
<? $this->start("prelayout_header"); ?>
	<?= $this->element("site_css"); ?>
<? $this->end(); ?>
<? $this->start("layout_footer"); ?>
<? $this->end(); ?>
<? if($this->get("share")) { ?>
	<? $this->start("title_controls"); ?>
		<?= $this->Share->share(); ?>
	<? $this->end("title_controls"); ?>
<? } ?>
<? $this->start("prelayout_header"); ?>
	<?= $this->element("header"); ?>
<? $this->end("prelayout_header"); ?>
<? } ?>
<? } ?>
