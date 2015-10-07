<? if(!empty($eventLocation)) { ?>
	<? if(!empty($eventLocation['EventLocation']['address'])) { ?>
	<div>
		<?= $this->element("../EventLocations/details", array('eventLocation'=>$eventLocation)); ?>
	</div>
	<? } ?>
<? } ?>
