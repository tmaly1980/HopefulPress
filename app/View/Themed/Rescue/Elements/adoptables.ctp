<? if(empty($type)) { $type = null; } ?>
<? if(!$this->fetch("adoptables_rendered")) { ?>
	<?= $this->requestAction(array('plugin'=>'rescue','controller'=>'adoptables','action'=>'widget',$type,'hostname'=>$hostname),array('return')); ?>
	<?#= $this->requestAction("/rescue/adoptables/widget/$type",array('return')); ?>
<? $this->assign("adoptables_rendered",true); } ?>
