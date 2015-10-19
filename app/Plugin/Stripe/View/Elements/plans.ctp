<? 
Configure::load("Stripe.billing");
$billing = Configure::read("Billing");
extract($billing);
if(empty($plan)) { $plan  = null; }
?>
<div class='row'>

	<? foreach($planList as $planId=>$enabled) { if(!$enabled) { continue; }; $planDetails = $plans[$planId]; ?>
	<div class='col-md-4'>
	<div class='border padding10 rounded lightgreybg '>
		<h3 class='borderbottom5 marginbottom10'><?= $planDetails['metadata']['title'] ?></h3>
		<div class='minheight300'>
			<?= $planDetails['metadata']['description']; ?>
		</div>
		<hr/>
		<div class='medium'>
			<? if(!empty($signup)) { ?>
			Free 30-day trial, then just
			<? } ?>
			<div class='row'>
			<div class='col-xs-6'>
			<?= sprintf("\$%u/%s", $planDetails['amount']/100, $planDetails['interval']); ?>
			<br/>
			<br/>

			<? $yearlyPlanId = "{$planId}_yearly"; ?>

			<? $yearlyPlan = !empty($plans[$yearlyPlanId]) ? $plans[$yearlyPlanId] : null; ?>

			<? if(!empty($plan) && $planId == $plan && !$disabled) { ?>
				<b>Current Plan</b>
			<? } else if (!empty($signup)) { ?>
				<?= $this->Html->link("Free Trial ".$this->Html->g("chevron-right"), "/signup/$planId",array('class'=>'btn btn-primary')); ?>
			<? } else { ?>
				<?= $this->Html->add("Monthly Plan", array('action'=>'upgrade',$planId),array('class'=>'btn-primary')); ?>
			<? } ?>
			</div>

			<? if(!empty($yearlyPlan)){ ?>
			<div class='col-xs-6'>
			<?= sprintf("\$%u/year", $yearlyPlan['amount']/100); ?>
			<br/>
			<b><?= sprintf("(%.0f%% off)", (1 - $yearlyPlan['amount']/($planDetails['amount']*12))*100); ?></b>
			<? if($yearlyPlanId == $plan) { ?>
			<br/>
				<b>Current Plan</b>
			<? } else if (!empty($signup)) { ?>
				<?= $this->Html->link("Free Trial ".$this->Html->g("chevron-right"), "/signup/$yearlyPlanId",array('class'=>'btn btn-success')); ?>
			<? } else { ?>
				<?= $this->Html->add("Annual Plan", array('action'=>'upgrade',$yearlyPlanId), array('class'=>'btn-success')); ?>
			<?  } ?>
			</div>
			<? } ?>

			</div>

		</div>
	</div>
	</div>
	<? } ?>
</div>

