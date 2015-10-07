<? $this->assign("container_class", " block"); # Dummy content, to avoid padding ?>
<? $this->assign("maincol_class", " block"); # Dummy content, to avoid padding ?>
<?#= $this->element("Www.sidebar"); ?>
<? $this->start("layout_footer");?>
<div class='center_align'>
	&copy; <?= date('Y'); ?> <a  href='http://www.hopefulpress.com/'>Hopeful Press</a>
</div>
<? $this->end();  ?>
<? $this->start("prelayout_header"); ?>
	<link rel="stylesheet" type="text/css" href="/www/css/www.css"/> <!-- custom portion -->
	<? if(Configure::read("blog")) { ?>
	<link rel="stylesheet" type="text/css" href="/www/css/blog.css"/> <!-- custom portion -->
	<? } ?>
	<? if($this->layout != 'Www.plain') { ?>
	<?= $this->element("Www.header"); ?>
	<? } ?>
<? $this->end(); ?>
<? if($this->get("share")) { ?>
	<? $this->prepend("title_controls"); ?>
		<?= $this->Share->share(true); ?>
	<? $this->end("title_controls"); ?>
<? } ?>
<?= $this->element("Core.../Layouts/core"); ?>
