<? $this->assign("page_title", "Update your account plan"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("View all plans", array('action'=>'plans')); ?>
<? $this->end();  ?>
<div class='form row'>
<?= $this->Form->create("StripeBilling"); ?>
	<div class='col-md-6'>
		<h3>Payment Information</h3>
		<div id='PaymentDetails'>
		<!-- always  ask for payment information unless has a current subscription  -->
		<? if(!empty($subscription['source'])) { $card = $subscription['source']; ?>
        		<b>Payment Method:</b>
        		<?= !empty($card['type']) ? $card['type'] : "Card" ?> ending in <?= $card['last4'] ?>
        		<br/>
        		(Expires <?= sprintf("%02u/%02u", $card['exp_month'], $card['exp_year']%100) ?>)
		<? } else { ?>
        		<?= $this->Form->hidden("StripeBilling.source.object", array('value'=>'card')); # REQD ?>                                                                    
        		<?= $this->Form->input("StripeBilling.source.number", array('label'=>'Credit Card Number','required'=>1,'length'=>16,'class'=>'width225','maxlength'=>16,'counter'=>false,'filter'=>"/[0-9]+/")); ?>
        		<?= $this->Form->expiration('StripeBilling.source.exp_month','StripeBilling.card.exp_year'); ?>
        		<?= $this->Form->input("StripeBilling.source.name", array('label'=>'Cardholder Name','class'=>'width225')); ?>
		<? } ?>
		</div>
	</div>
	<div class='col-md-6'>
		<h3>Select a Term</h3>
		<?
			$yearlyPlan = $plan."_yearly";
			$monthly = $billing['plans'][$plan];
			$yearly = $billing['plans'][$yearlyPlan];
			$monthlyAmount = $monthly['amount']/100;
			$yearlyAmount = $yearly['amount']/100;
			$savings = 100-($monthlyAmount*12-$yearlyAmount)/$yearlyAmount;
		?>
		<div class='row'>
			<div class='col-md-6'>
				<?= $this->Form->radio("plan",array($plan=>"Month-to-month"),array('value'=>$plan)); ?>
				<p>No long-term commitment, cancel any time</p>
				<?= sprintf("$%.02f/month", $monthly); ?>
			</div>
			<div class='col-md-6'>
				<?= $this->Form->radio("plan",array($yearlyPlan=>"Yearly")); ?>
				<p>
					Pay a year in advance, save <?= sprintf("%u%%", $savings); ?>!
				</p>
				<?= sprintf("$%.02f/year", $yearly); ?>
			</div>
		</div>
	</div>
	<?= $this->Form->save("Update plan"); ?>
<?= $this->Form->end(); ?>
</div>
