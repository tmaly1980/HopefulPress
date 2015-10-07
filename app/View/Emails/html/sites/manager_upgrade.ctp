<?
$website_url = "http://" . (!empty($current_site['Site']['domain']) ? $current_site['Site']['domain'] : $current_site['Site']['hostname'] . ".$default_domain" );
?>
<h2>Website Subscription Upgraded: <?= $current_site['Site']['title'] ?> (<?= $website_url ?>)</h2>

<p>This site just upgraded their subscription to a paid account.

<p>View Website Details: <?= $this->Html->link("http://".$current_site['Site']['hostname'].".$default_domain/manager"); ?></p>

