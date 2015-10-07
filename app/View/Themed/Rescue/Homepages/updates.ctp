<? 
$sidebar_content  = !empty($homepage['Homepage']['sidebar_content']) ? $homepage['Homepage']['sidebar_content'] : null;
$sidebar_title  = !empty($homepage['Homepage']['sidebar_content']) ? $homepage['Homepage']['sidebar_title'] : null;
$mailinglist = $this->requestAction("/newsletter/subscribers/widget",array('return')); 

$news = $this->element("../NewsPosts/widget",array('wide'=>1));
$events = $this->element("../Events/widget");
$photos = $this->element("../PhotoAlbums/widget");
$videos = null;# BROKEN FOR NOW # $this->element("Videos.../Videos/widget");

$nomaincontent = empty($news) && empty($photos) && empty($videos);

$adoptables = $this->element("adoptables",array('type'=>($nomaincontent?'block':'carousel')));
$successes = $this->requestAction("/rescue/adoption_stories/widget",array('return'));

$has_sidebar = (!empty($events) || !empty($sidebar_content) || !empty($mailinglist) || (!empty($adoptables) && !$nomaincontent));
?>
<div id='updates' class='row widgets'>
<? if($has_sidebar) { ?>
	<div class='col-md-8 padding5 row'>
<? } ?>

<? if($nomaincontent && !empty($adoptables)) { ?>
<div class='col-md-12'>
	<?= $adoptables ?>
</div>
<? } ?>

<? if(!empty($news)) { ?>
<div class='col-md-12'>
	<?= $news ?>
</div>
<? } ?>

<? /* if(!empty($events)) { ?>
	<?= $events ?>
<? } */ ?>

<? if(!empty($photos)) { ?>
<div class='col-md-12'>
	<?= $photos ?>
</div>
<? } ?>

<? if(!empty($videos)) { ?>
<div class='col-md-12'>
	<?= $videos ?>
</div>
<? } ?>

<? if(!empty($successes)) { ?>
<div class='col-md-12'>
	<?= $successes ?>
</div>
<? } ?>

<? if($has_sidebar) { ?>
</div>
	<div class="col-md-4 padding5">
		<?= $events ?>
		<? if(!$nomaincontent) { ?>
			<?= $adoptables ?>
		<? } ?>
		<?= $mailinglist ?>
		<? if(!empty($sidebar_content)) { ?>
		<div class='widget'>
		<? if(!empty($sidebar_title)) { ?>
			<h3><?= $sidebar_title ?></h3>
		<? } ?>
		<div>
			<?= $sidebar_content ?>
		</div>
		</div>
		<? } ?>
	</div>
<? } ?>

</div>
<div class='clear'></div>
