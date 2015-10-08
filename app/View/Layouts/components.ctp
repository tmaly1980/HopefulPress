<? if(!empty($rescue)) { # Currently on a site. ?>
<? if(empty($preview_wrapper)) { ?>
<!-- start main -->
<?= $this->element("defs"); ?>
<? $this->start("prelayout_header"); ?>
	<?= empty($this->request->params['prefix']) ? $this->element("rescue/css") : null; ?>
<? $this->end(); ?>
<? $this->start("layout_footer"); ?>
<? $this->end(); ?>
<? if($this->get("share")) { ?>
	<? $this->start("title_controls"); ?>
		<?= $this->Share->share(); ?>
	<? $this->end("title_controls"); ?>
<? } ?>
<? $this->start("prelayout_header"); ?>
	<?= empty($this->request->params['prefix']) ? $this->element("rescue/header") : null; ?>
<? $this->end("prelayout_header"); ?>
<? } ?>
<? } ?>
