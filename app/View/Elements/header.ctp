<?
$logo = trim($this->element("header/logo"));
$title = trim($this->element("header/title"));
$subtitle  = trim($this->element("header/subtitle"));
$right = trim($this->element("header/right"));
$social  = trim($this->element("header/social"));
?>
<div id='header'>
	<div class=''>
		<?= $this->element("header_table",array('logo'=>$logo,'title'=>$title,'subtitle'=>$subtitle,'right'=>$right,'social'=>$social)); ?>
	</div>
	<div class='clear'></div>
</div>
<?= $this->element("nav"); ?>
