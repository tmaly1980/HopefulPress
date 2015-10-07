<? if(empty($updates['videos']) && !$this->Html->can_edit()) { return; } ?>

<? $prefix = Configure::read("members_only") ? "members" : "user"; ?>
<? $pid = Configure::read("project_id"); ?>
<? $project = Configure::read("project"); ?>
<div class='widget'>
<h3>
	<?= $this->Html->link("Recent Videos", array($prefix=>1,'plugin'=>null,'controller'=>'videos','action'=>'index')); ?>
</h3>
<? if($this->Html->can_edit()) { ?>
<div class='alert-warning dashed border2'>
	<?= $this->Html->add(empty($updates['videos']) ? "Add your first video":"Add another video", array($prefix=>1,'plugin'=>null,'controller'=>'videos','action'=>'add','project_id'=>$pid),array('title'=>'Add Video','class'=>'')); ?>
</div>
<? } ?>
<? if(!empty($updates['videos'])) { ?>
	<div class=''>
	<? foreach($updates['videos'] as $album) { ?>
		<div class='paddingbottom25'>
			<?
				$imgsrc = !empty($album['Photo'][0]['id']) ? "/photos/thumb/".$album['Photo'][0]['id'] : "/images/no-photo.png";
				$img = $this->Html->image($imgsrc, array('class'=>'border')); 
				$title = !empty($album['PhotoAlbum']['title']) ? $album['PhotoAlbum']['title'] : "Untitled Album";
			?>
			<div align='center'>
			<?= $this->Html->link($img, array('controller'=>'photo_albums','action'=>'view',$album['PhotoAlbum']['idurl']), array()); ?>
			</div>
			<?= $this->Html->titlelink($title, array('controller'=>'photo_albums','action'=>'view',$album['PhotoAlbum']['idurl']), array()); ?>
			<div class=''>
				<?= $this->Time->mondy($album['PhotoAlbum']['created']); ?> &ndash;
				<?= !empty($album['Photo']) ? count($album['Photo']) : "No" ?> photos
			</div>
		</div>
	<? } ?>
	<? if(!empty($updates['photoAlbums'])) { ?>
	<?= $this->Html->link("More photos ".$this->Html->g("chevron-right"), array('controller'=>"photo_albums"), array('class'=>'btn more right_align medium bold')); ?>
	<? } ?>
	</div>
<? } ?>
</div>

