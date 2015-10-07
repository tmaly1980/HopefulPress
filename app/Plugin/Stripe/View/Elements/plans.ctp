<div class='row'>

	<? foreach($plans as $planId=>$planDetails) { 
	?>
	<div class='col-md-4'>
	<div class='border padding10 rounded lightgreybg '>
		<h3 class='borderbottom5 marginbottom10'><?= $planDetails['metadata']['title'] ?></h3>
		<div class='minheight250'>
			<?= $planDetails['metadata']['description']; ?>
		</div>
		<hr/>
		<div class='bold medium'>
			<? if(!empty($signup)) { ?>
			Free 30-day trial, then just
			<? } ?>
			<?= sprintf("\$%u/mo", $planDetails['amount']/100); ?>
			<br/>
			<br/>

			<? $yearlyPlan = !empty($yearlyPlans["{$planId}_yearly"]) ? $yearlyPlans["{$planId}_yearly"] : null; ?>

			<? # XXX TODO DO NOT LET THEM SWITCH OUT OF YEARLY PLANS.... or at least don't offer a refund.... 

			# When they switch plans, we MAY need to have a start date so it matches the end of the previous billing cycle (
			#
			# RIGHT NOW: Previous billing cycle completes; They get billed the full amount immediately
			#
			# If they upgrade: ?? bill the difference immediately from now until next billing cycle????
			#
			# If they downgrade:
			#
			# Worry about it when they ASK!
			
			?>


			<? if(!empty($plan) && $planId == $plan && !$disabled) { ?>
				<b>Current Plan</b>
			<? } else if (!empty($signup)) { ?>
				<?= $this->Html->link("Free Trial ".$this->Html->g("chevron-right"), "/signup/$planId",array('class'=>'btn btn-success')); ?>
			<? } else { ?>
				<?= $this->Html->add("Select Plan".(!empty($yearlyPlan)?" (Month-to-Month)":""), array('action'=>'upgrade',$planId)); ?>
			<? } ?>

			OR pay annually and get <?= sprintf("%u%% off!", $planDetails['amount']/$yearlyPlan['amount']*100); ?>

			<?= sprintf("\$%u/year", $yearlyPlan['amount']/100); ?>
			<?= $this->Html->add("Select Plan (Annual)", array('action'=>'upgrade',$yearlyPlanId)); ?>
			<? } ?>
		</div>
	</div>
	</div>
	<? } ?>
</div>

