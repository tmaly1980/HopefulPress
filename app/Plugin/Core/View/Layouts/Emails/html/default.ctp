<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
        <!-- title doesnt matter in emails -->
	<base href="<?= Router::url("/", true); ?>"/>
</head>

<body>
<? if($site_title = Configure::read("site_title")) { ?>
<h4><?= $site_title ?></h4>
<? } ?>

<?php echo $this->fetch('content');?>

<? if(!empty($data['Email']['message'])) { ?>
<br/>
<br/>
<div class='bold'> 
<? 
	if(!empty($sender['name']))
	{
		echo "{$sender['name']} says: ";
	} else if (!empty($data['Email']['from'])) {
		echo "{$data['Email']['from']} says: ";
	} else {
		echo "The sender says: ";
	}
?>
</div>
<div class=''>
	<?= nl2br($data['Email']['message']); ?>
</div>
<? } ?>

<hr/>
<? if($site_title = Configure::read("site_title")) { ?>
<div>
	Email sent via <?= $this->Html->link($site_title, Router::url("/", true)); ?>
</div>
<? } ?>
<? if($app_url = Configure::read("app_url") && $app_title = Configure::read("app_title")) { ?>
<div>
	Powered by <?= $this->Html->link($app_title, $app_url); ?>
</div>
<? } ?>

</body>
</html>
