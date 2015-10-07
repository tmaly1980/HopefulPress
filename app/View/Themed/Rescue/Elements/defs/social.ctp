<? $this->start("social_content"); ?>
<? if(!empty($nav['donationsEnabled'])) { ?>
	<?= $this->Html->link("Donate", "/donation", array('class'=>'btn btn-warning controls')); ?>
<? } ?>
<? $this->end("social_content"); ?>
