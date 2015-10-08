<? if(empty($type)) { $type = null; } ?>
<? if(!$this->fetch("adoptables_rendered")) { ?>
	<?= $this->requestAction(array('controller'=>'adoptables','action'=>'widget',$type,'rescue'=>$rescuename),array('return')); ?>
	<?#= $this->requestAction("/rescue/adoptables/widget/$type",array('return')); ?>
<? $this->assign("adoptables_rendered",true); } ?>
