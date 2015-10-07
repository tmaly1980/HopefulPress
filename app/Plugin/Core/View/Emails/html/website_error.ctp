<?
$ip = $_SERVER['REMOTE_ADDR'];
App::uses("HostInfo",  "Core.Lib");
$geoip = HostInfo::geoip($ip);
#$provider = HostInfo::whois($ip, "/owner/organization");

$loc = array();
if(!empty($geoip['city'])) { $loc[] = $geoip['city']; }
if(!empty($geoip['region'])) { $loc[] = $geoip['region']; }
if(!empty($geoip['country_name'])) { $loc[] = $geoip['country_name']; }
?>
<h1>Website Error</h1>

<p>Message: <?= $error->getMessage() ?>
<p>URL: <?= Router::url($url,true) ?>
<p>Client IP: <?= $_SERVER['REMOTE_ADDR'] ?>  (<?= gethostbyaddr($_SERVER['REMOTE_ADDR']) ?>)
<p>Client Location: <?= join(", ", $loc); ?>
<p>Browser: <?= $_SERVER['HTTP_USER_AGENT'] ?>
<p>Referer: <?= $_SERVER['HTTP_REFERER'] ?>

<?= $this->element("exception_stack_trace"); ?>

<p>Session:
<pre><?= print_r($_SESSION,true); ?></pre>

<p>Request info:
<pre><?= print_r($request,true); ?></pre>

