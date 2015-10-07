<?
$website_url = "http://" . (!empty($current_site['Site']['domain']) ? $current_site['Site']['domain'] : $current_site['Site']['hostname'] . ".$default_domain" );
?>
<h2>Your have been assigned as the site owner for <?= $current_site['Site']['title'] ?></h2>

<p>As the site owner, you will be able to update content, customize your website design, add users, update billing, or cancel the website at any time.

<p>Please keep this email for your records. Your website's address is:

<p><b><?= $this->Html->link($website_url."/", $website_url."/"); ?></b>

<p>To manage your website, simply visit the above URL and click on 'Sign In' in the top corner of the screen. You can also visit the following link directly:

<p><b><?= $this->Html->link($website_url."/admin", $website_url."/admin"); ?></b>

<p> Remember, your email is used to sign in. It is: <b><?= $current_user['email'] ?></b>
