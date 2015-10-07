<? $action = $this->request->params['action']; ?>
<? if($action != 'index') { ?>
	<?= $this->Html->blink("list", "Events List", array('action'=>'index'), array('class'=>'btn-primary white','title'=>"View List")); ?>
<? } else if ($action != 'calendar') { ?>
	<?= $this->Html->blink("calendar", "Events Calendar", array('action'=>'calendar'), array('class'=>'btn-primary white','title'=>"View Calendar")); ?>
<? } ?>
