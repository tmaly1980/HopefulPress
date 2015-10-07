<? $this->assign("page_title", "Tickets"); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->me()) { ?>
	<?= $this->Html->add("Ask a ticket",array('user'=>1,'action'=>'add')); ?>
<? } ?>
<? $this->end(); ?>

<div class='index'>
<? if(!$this->Html->me()) { ?>
	<?= $this->Html->blink("user", "Sign in", "/user/users/login",array('class'=>'btn-success')); ?> to submit a ticket
<? } ?>
<? if(!empty($tickets)) { ?>
	<h3>Current Tickets</h3>
	<?= $this->element("../Tickets/list", array('tickets'=>$tickets)); ?>
<? } ?>

<? if(!empty($previous_tickets)) { ?>
<h3>Previous Tickets</h3>
	<?= $this->element("../Tickets/list", array('tickets'=>$previous_tickets)); ?>

<?= $this->element("pager"); ?>
<? } ?>
</div>
