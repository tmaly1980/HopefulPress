<h1>Website Error</h1>

<p>Message: <?= $error->getMessage() ?>
<p>URL: <?= Router::url($url,true) ?>
<p>Client IP: <?= $_SERVER['REMOTE_ADDR'] ?>
<p>Browser: <?= $_SERVER['HTTP_USER_AGENT'] ?>
<p>Referer: <?= $_SERVER['HTTP_REFERER'] ?>

<?= $this->element("exception_stack_trace"); ?>

<p>Session:
<pre><?= print_r($_SESSION,true); ?></pre>

<p>Request info:
<pre><?= print_r($request,true); ?></pre>

