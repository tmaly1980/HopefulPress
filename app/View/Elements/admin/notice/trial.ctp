<?
if(!$this->Rescue->admin()) { return false; }
$created = $rescue["Rescue"]["created"];
$trial_days = floor((time() - strtotime($created))/(24*60*60));
$trial_days_max = Configure::read("trial_days");
$trial_days_percent = $trial_days/$trial_days_max*100;

$class = 'info';
if($trial_days_percent > 50) { $class = 'warning'; }
if($trial_days_percent > 75) { $class = 'danger'; }
?>
<div class='alert alert-<?= $class ?>'>
	<? if($trial_days > $trial_days_max) { ?>
	<b>Your free trial has expired!</b> Please <?= $this->Html->link("upgrade to a paid account immediately", "/admin/billing"); ?> to prevent interruption to your website.
	<? } else { ?>
	Your free trial will expire in <?= $trial_days_max-$trial_days ?> days. Please <?= $this->Html->link("upgrade to a paid account", "/admin/billing"); ?> before it's too late.
	<? } ?>
</div>
