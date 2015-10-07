<? if(!empty($eventContact)) { ?>
	<? if(!empty($eventContact['EventContact']['name'])) { ?>
	<div>
		<?= $this->element("../EventContacts/details", array('eventContact'=>$eventContact)); ?>
	</div>
	<? } ?>
<? } ?>
