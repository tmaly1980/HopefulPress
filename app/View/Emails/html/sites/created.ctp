<?
$website_url = "http://" . (!empty($current_site['Site']['domain']) ? $current_site['Site']['domain'] : $current_site['Site']['hostname'] . ".$default_domain" );
?>
<h2>Your website for <?= $current_site['Site']['title'] ?> has been created</h2>

<p>Please keep this email for your records. Your website's new address is:

<p><b><?= $this->Html->link($website_url."/", $website_url."/"); ?></b>

<? if(empty($current_site['Site']['domain'])) { ?>
<p>If you want to use your own domain name, such as <b>http://OrganizationName.com/</b> instead of a <b>.<?=$default_domain?></b> address, you'll be able to add it later.
<?} ?>

<p>To manage your website, simply visit the above URL and click on 'Sign In' in the top corner of the screen. You can also visit the following link directly:

<p><b><?= $this->Html->link($website_url."/admin", $website_url."/admin"); ?></b>

<p> After you finish the setup process, you'll be able to start adding new content or create user accounts so others can help too.

<p> Remember, your email is used to sign in. It is: <b><?= $current_user['email'] ?></b>

