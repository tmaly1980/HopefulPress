<? $disabled = $this->Rescue->get("disabled"); ?>
<? $this->start("title_controls"); ?>
	<?=  $this->Html->back("Back to Website Details",array('admin'=>1,'plugin'=>null,'controller'=>'rescues','action'=>'edit')); ?>
<? if($this->Html->manager()) { ?>
	<?= $this->Html->edit("Change Plan", array('manager'=>1,'action'=>'edit')); ?>
<? }?>
<? /* if($this->Rescue->owner() && !$disabled) { ?>
	<?= $this->Html->remove("Cancel Website", array('admin'=>1,'action'=>'disable'),array('confirm'=>'Are you sure you want to cancel your website?')); ?>
<? } */ ?>
<? $this->end("title_controls"); ?>
<? $mode = Configure::read("Stripe.mode"); ?>
<? $this->assign("page_title", "Website Billing ".($mode == 'Test' ? ' - TEST':'')); ?>
<?
$signup = $this->Rescue->get("created");
$days = floor( (time() - strtotime($signup))/(60*60*24) );
$expires = 30 - $days;
$trial = $this->Rescue->get("trial");
if(empty($plans[$plan])) { $plan = null; } # Invalid/trial. Treat like need to pick a plan.
?>
<div>
	<? if($disabled) { ?>
		<div class='alert alert-danger'>
			Your website is currently disabled. 
			<? if($this->Rescue->get("stripe_id")) { # Billing on file, let them restore as is ?>
			<?= $this->Html->add("Restore Your Website", array('action'=>'restore')); ?> to continue.
			<? } ?>
		</div>
	<? } else if(empty($plan) || !empty($trial)) { ?>
		<? if($expires > 0) { ?>
			<div class='alert alert-info'>
				Your free trial expires in <?= $expires ?> days. Please consider upgrading to a paid account before then.
			</div>
		<? } else { ?>
			<div class='alert alert-warning'>
				Your free trial has expired. To continue your website service, please upgrade to a paid account.
			</div>
		<? } ?>
	<? } ?>

	<? if(!$disabled && !empty($plan)){ ?>
	<div class='row margintop20 marginbottom20'>
		<div class='col-md-6'>
			<b>Current Plan:</b>
			<? if(!empty($plans[$plan])) { ?>
				<?= $plans[$plan]['metadata']['title']; ?>
				&mdash;
				<?= sprintf("$%u / %s", $plans[$plan]['amount']/100, $plans[$plan]['interval']) ?>
			<? } else { ?>
				Free Trial
			<? } ?>
		</div>
		<div class='col-md-6'>
			<? if(!empty($card)) { ?>
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
				<?= $this->Html->link("Update billing information", array('plugin'=>'stripe','controller'=>'stripe_billing','action'=>'edit'), array('class'=>'color','Xupdate'=>'billing_view')); ?>
			<? } else if(!empty($plan)) { # Plan selected but nothing on file.... Can add card if plan was selected at signup... ?>
			
				<?= $this->Html->add("Add payment details", array('plugin'=>'stripe','controller'=>'stripe_billing','action'=>'edit'), array('class'=>'','Xupdate'=>'billing_view')); ?>
				<div class='alert alert-warn'>
					Please provide payment details to continue past your free trial.
				</div>
			<? } ?>
		</div>
	</div>
	<? } ?>

	<hr/>

	<h2><?= $disabled ? "Restore Website" : (empty($plan) ? "Choose a Plan" : "Change Your Plan") ?></h2>
	<?= $this->element("Stripe.plans"); ?>

	<!-- todo cancel website -->
</div>
