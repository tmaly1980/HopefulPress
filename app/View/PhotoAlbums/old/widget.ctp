<? $pid = $this->Admin->project('id'); ?>
<div class='PhotoAlbum widget margintop10'>
<h3>
	<?#= $this->Html->add_link(null,'photo_albums'); ?>
	Recent Photos</h3>
<div class='clear'></div>
<div class='items'>
	<? if(empty($photoAlbums)) { ?>
		<div class='nodata'>There are no photos yet.
			<? if(!empty($in_admin) && $this->Admin->access()) { ?>
				<?= $this->Html->link("Create your first photo album", array('plugin'=>null,'controller'=>'photo_albums','action'=>'add','project_id'=>$pid), array('class'=>'color')); ?>
			<? } ?>
		</div>
	<? } else { ?>
		<? foreach($photoAlbums as $album) { ?>
		<div class='item left margin5 height175 width150'>
			<?
				$imgsrc = !empty($album['Photo'][0]['id']) ? "/photos/thumb/".$album['Photo'][0]['id']."/150x100" : "/images/no-photo.png";
				$img = $this->Html->image($imgsrc, array('class'=>'border')); 
				$title = !empty($album['PhotoAlbum']['title']) ? $album['PhotoAlbum']['title'] : "Untitled Album";
			?>
			<?= $this->Html->link($img, array('controller'=>'photo_albums','action'=>'view',$album['PhotoAlbum']['idurl'],'project_id'=>$album['PhotoAlbum']['project_id']), array()); ?>
			<br/><?= $this->Html->link($title, array('controller'=>'photo_albums','action'=>'view',$album['PhotoAlbum']['idurl'],'project_id'=>$album['PhotoAlbum']['project_id']), array('class'=>'title')); ?>
			<div class=''>
				<?= $this->Date->mondy($album['PhotoAlbum']['created']); ?> &ndash;
				<?= !empty($album['Photo']) ? count($album['Photo']) : "No" ?> photos
			</div>

		</div>
		<? } ?>

		<div class='clear'></div>

	<? } ?>
</div>
<div align='right'>
	<?= $this->Html->link("More photos...", array('plugin'=>null,'controller'=>'photo_albums','project_id'=>$pid),array('class'=>'small more')); ?>
</div>
</div>
