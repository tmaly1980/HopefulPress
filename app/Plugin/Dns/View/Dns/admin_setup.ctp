<? $loc = $this->params['controller']; # setup OR sites ?>
<? $setup = ($loc == 'setup'); ?>
<? if(!$this->Admin->current_site("domain")) { ?>
	<? if($this->Admin->current_site("status") != 'Trial') { ?>
		<?= $this->Html->link("Add your own domain name", array('plugin'=>'dns','controller'=>'dns','action'=>'add','setup'=>true), array('class'=>'color orange medium bold','update'=>'Site_dns')); ?>
	<? } else { ?>
		<div class='warn'>
			To use your own domain name (such as <i>http://organizationname.com</i>), please <?= $this->Html->link("upgrade to a paid account", array('plugin'=>'sites','controller'=>'stripe_billing_profile','action'=>'add','setup'=>$setup), array('class'=>'color green')); ?>
			<!-- must go back to domain setup once done! -->
		</div>
	<? } ?>
<? } else { ?>
	<p class='bold'>Your domain name has been successfully added.</p>
<? } ?>

