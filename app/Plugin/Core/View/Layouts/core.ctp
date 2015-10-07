<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <? 
    	$favicon = Configure::read("favicon"); 
	echo $this->Html->meta('icon', $favicon);
	if($rss = Configure::read("rss"))
	{
		echo $this->Html->meta('rss', $rss);
	}
	echo $this->element('Core.meta');
	echo $this->element("Core.css"); 
	echo $this->element("css"); 
	echo $this->element("Core.js"); 
	echo $this->element("js"); 
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script'); # May depend on libs
    ?>
    <? $title = $this->fetch("browser_title"); ?>
    <? if($page_title = $this->fetch("page_title")) { $title = $page_title; }; ?>

    <title>
    	<?= !empty($title) ? "$title | " : "" ?>
	<?= Configure::read("site_title"); ?>
    </title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body id='top' class='<?= $this->layout ?> <?= $this->request->params['controller'] ?> <?= $this->request->params['action'] ?>'>
  <?= $this->fetch("prepend_body"); ?>
<div id='body_wrapper' class=''>
  <?= $this->fetch("before_maincol"); ?>
  <? $main_class = $this->fetch("maincol_class","col-sm-9 col-md-10"); ?>
  <div id='main_wrapper' class='<?= $this->fetch("main_wrapper_class"); ?> <?= preg_match("/col-/", $main_class) ? "row":"" ?>'>
  	<?= $this->fetch("prepend_main_wrapper"); ?>
  <? $page_class = $this->fetch("container_class")." ".$this->fetch("page_class"); # Used inside ?>
  <div id='main' class='<?= $main_class ?> <?= preg_match("/col-/", $page_class) ? "row" : "" ?>'>
  	<?= $this->fetch("prepend_maincol"); ?>
	<? if(!empty($preview_wrapper)) { # Also remove page_wrapper, main_content, etc, since will be used in preview sub-calls ?>
		<?= $this->fetch("content"); ?>
	<? } else { ?>
 		<?= $this->element("Core.../Layouts/main"); ?>
	<? } ?>
  	<?= $this->fetch("append_maincol"); ?>
  </div>
  </div>
  <?= $this->fetch("after_maincol"); ?>
</div>

  <?= $this->fetch("append_body"); ?>
  </body>
</html>
