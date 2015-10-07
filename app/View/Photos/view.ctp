<? $this->start("admin_controls"); ?>
	<?= $this->Html->blink("back", "Back to album", array('controller'=>'photo_albums','action'=>'view',$photoAlbum['PhotoAlbum']['id'])); ?>
<? $this->end("admin_controls"); ?>
<? $pid = null;#$this->Admin->project('id'); ?>
<? #$project_title = $this->Admin->project('title'); ?>
<? $this->assign("page_title", $photoAlbum['PhotoAlbum']['title']); #"View Photos".(!empty($project_title)? " For $project_title":"")); ?>
<? $album_id = $photoAlbum['PhotoAlbum']['id']; ?>

<div class="photos view fontify <?#= $this->Admin->fontsize('default'); ?>">

<?
$photos = Set::extract("/Photo/id", $photoAlbum);
$ix = 0;
foreach($photos as $id)
{
	if($id == $photo['Photo']['id'])
	{
		break;
	}
	$ix++;
}
$nextid = ($ix < count($photos)-1) ? $photos[$ix+1] : $photos[0];
$previd = ($ix > 0) ? $photos[$ix-1] : $photos[count($photos)-1];

?>

<div align='' class='marginbottom10'>
	Photo <?= $ix+1 ?> of <?= count($photos); ?>
	
</div>

<div class='lightgreybg center_align relative'>
	<? $img = $this->Html->image("/photos/image/".$photo['Photo']['id']); ?>
	<?= $this->Html->link($img, array('action'=>'view',$nextid,'project_id'=>$pid,'photo_album_id'=>$album_id), array('title'=>'Click to see next picture')); ?>
	<div style="position: absolute; left: 2em; top: 50%; margin-top: -16px;">
		<?= $this->Html->blink('triangle-left', '', array('action'=>'view',$previd,'project_id'=>$pid,'photo_album_id'=>$album_id), array('class'=>'btn-lg')); ?>
	</div>
	<div style="position: absolute; right: 2em; top: 50%; margin-top: -16px;">
		<?= $this->Html->blink('triangle-right', null, array('action'=>'view',$nextid,'project_id'=>$pid,'photo_album_id'=>$album_id), array('class'=>'btn-lg')); ?>
	</div>
</div>
<p id='Photo_caption_<?= $photo['Photo']['id'] ?>' class='margintop15'>
	<?= nl2br($photo['Photo']['caption']) ?>
</p>
<? if($this->Html->can_edit($photo['Photo'])) { ?>
<script>
$('#Photo_caption_<?= $photo['Photo']['id'] ?>').inline_edit({link: 'Add caption/Edit caption'});
</script>
<? } ?>

</div>
