<? if(!empty($subscription['card'])) { $card = $subscription['card']; ?>
	<b>Payment Method:</b>
	<?= !empty($card['type']) ? $card['type'] : "Card" ?> ending in <?= $card['last4'] ?>
	<br/>
	(Expires <?= sprintf("%02u/%02u", $card['exp_month'], $card['exp_year']%100) ?>)
	<? $nowyear = date('Y'); $nowmonth = date('m'); ?>
	<? if($card['exp_year'] < $nowyear || ($card['exp_year'] == $nowyear && $card['exp_month'] <= $nowmonth)) { ?>
	<div class='warn'>
	Your card is expired. Please update your payment information to continue.
	</div>
	<? } ?>
	<?= $this->Html->link("Update billing information", array('action'=>'edit_billing'), array('data-update'=>'PaymentDetails','class'=>'color','Xupdate'=>'billing_view')); ?>
<? } else { ?>
	<?= $this->element("../Rescues/rescuer_edit_billing"); ?>
<? } ?>
