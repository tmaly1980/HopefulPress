<? $this->assign("maincol_class", " block"); # Dummy content, to avoid padding ?>
<?#= $this->element("Www.sidebar"); ?>
<? $this->start("layout_footer");?>
<div class='center_align'>
	&copy; <?= date('Y'); ?> <a  href='http://www.hopefulpress.com/'>Hopeful Press</a>
</div>
<? $this->end();  ?>
<? $this->start("prelayout_header"); ?>
	<link rel="stylesheet" type="text/css" href="/www/css/www.css"/> <!-- custom portion -->
	<?= $this->element("Support.header"); ?>
<? $this->end(); ?>
<?= $this->element("Core.../Layouts/core"); ?>
