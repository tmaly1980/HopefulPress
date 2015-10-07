<? 
$prefix = Configure::read("members_only") ? "members" : "user"; 
$pid = Configure::read("project_id"); 
$project = Configure::read("project"); 

$news = $this->element("../NewsPosts/widget");
$events = $this->element("../Events/widget");
$photos = $this->element("../PhotoAlbums/widget");
$videos = null;# BROKEN FOR NOW # $this->element("Videos.../Videos/widget");
?>
<div id='updates' class='row widgets'>

<? if(!empty($news)) { ?>
<div class='col-md-<?= !empty($events) ? 6 : 12 ?>'>
	<?= $news ?>
</div>
<? } ?>

<? if(!empty($events)) { ?>
<div class='col-md-<?= !empty($news) ? 6 : 12 ?>'>
	<?= $events ?>
</div>
<? } ?>

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

</div>
<div class='clear'></div>
