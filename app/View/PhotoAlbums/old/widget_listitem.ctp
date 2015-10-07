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
	<div class='left margintop5 marginright5'>
		<?= $this->Html->link($img, array('controller'=>'photos','action'=>'view',$id), array('title'=>$photo['caption'])); ?>
	</div>
<? } ?>
</div>
<div class='clear'></div>
<div align='right' class='small paddingtop10'>
<?= $this->Html->link("View all $count photos &raquo;", array('controller'=>'photo_albums','action'=>'view',$photoAlbum['PhotoAlbum']['idurl']), array()); ?>
</div>
