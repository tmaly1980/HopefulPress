<? if(!empty($eventContact)) { ?>
<div>

	<?= $this->element("../EventContacts/view"); ?>
	<div> <?= $this->Html->link('Edit contact information', array('action'=>'edit',$eventContact["EventContact"]['id']),array('class'=>'dialog mini')); ?> </div>
</div>
<? } ?>

