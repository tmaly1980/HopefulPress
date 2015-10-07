<? if(!empty($eventLocation)) { ?>
<div>
	<?= $this->element("../EventLocations/view"); ?>
	<div><?= $this->Html->link('Edit location details', array('action'=>'edit',$eventLocation["EventLocation"]['id']),array('class'=>'dialog mini')); ?></div>
</div>
<? } ?>

