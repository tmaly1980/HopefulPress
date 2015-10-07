<h2>Domain Name Added</h2>

<p><?= $this->Html->link($current_site['Site']['title'], "http://{$current_site['Site']['hostname']}.$default_domain"); ?> just requested a domain name:

<p><?= $domain ?> (<?= empty($new)?"EXISTING":"NEW REGISTRATION"; ?>)

<? if($new && !empty($failure)) { ?>
ERROR: Automatic DNS registration failed. Please manually register domain.
<? } ?>

<? if($new && !empty($billing_failure)) { ?>
ERROR: Could NOT bill customer for domain registration. Please make sure the domain is proeprly registered, then manually bill them for this domain registration.<br/>
MESSAGE: <?= $billing_failure ?>
<? } ?>
