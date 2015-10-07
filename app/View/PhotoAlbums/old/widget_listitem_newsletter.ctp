<?
	$title = !empty($photoAlbum['PhotoAlbum']['title']) ? $photoAlbum['PhotoAlbum']['title'] : "Untitled Album";
	$count = !empty($photoAlbum['Photo']) ? count($photoAlbum['Photo']) : 0;
?>
	<?= $this->Html->link($title, array('action'=>'view',$photoAlbum['PhotoAlbum']['idurl']), array('class'=>'title')); ?>
<div>
<? for($i = 0; $i < 4 && $i < count($photoAlbum['Photo']); $i++) { 
	$photo = $photoAlbum['Photo'][$i];
	$id = $photo['id'];
	$img = $this->Html->image("/photos/thumb/$id", array('class'=>'border')); 
?>
	<div style='float: left; margin: 5px 5px 0px 0px;'>
		<?= $this->Html->link($img, array('controller'=>'photos','action'=>'view',$id), array('title'=>$photo['caption'])); ?>
	</div>
<? } ?>
</div>
<div style='clear:both;'></div>
<div align='right' class='small' style='padding-top: 10px;'>
<?= $this->Html->link("View all $count photos &raquo;", array('controller'=>'photo_albums','action'=>'view',$photoAlbum['PhotoAlbum']['idurl']), array()); ?>
</div>
