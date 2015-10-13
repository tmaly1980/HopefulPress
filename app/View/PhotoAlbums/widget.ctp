<? if(empty($updates['photoAlbums']) && !$this->Html->me() /* && !$this->Html->can_edit() */) { return; } ?>

<? $prefix = Configure::read("members_only") ? "members" : "user"; ?>
<? $pid = Configure::read("project_id"); ?>
<? $project = Configure::read("project"); ?>
<div class='widget'>
<h3>
	<?= $this->Html->link("Recent Photos", array('plugin'=>null,'controller'=>'photo_albums','action'=>'index')); ?>
</h3>
<? if(false && $this->Html->can_edit()) { ?>
<div class='alert-warning dashed border2'>
	<?= $this->Html->add(empty($updates['photoAlbums']) ? "Add your first photo album":"Add another photo album", array('user'=>1,'plugin'=>null,'controller'=>'photo_albums','action'=>'add','project_id'=>$pid),array('title'=>'Add Photos','class'=>'')); ?>
</div>
<? } ?>
<? if(empty($updates['photoAlbums'])) { ?>
        <? if($this->Html->me()) { ?>
        <div class='dashed alert alert-info'>
                You have no photos yet.
                <?= $this->Html->add("Add some photos", array('user'=>1,'controller'=>'photo_albums','action'=>'add')); ?>
        </div>
        <? } ?>
<? } else { ?>
	<div class=''>
	<? foreach($updates['photoAlbums'] as $album) { ?>
		<div class='left padding10 width250 minheight225'>
			<?
				$imgsrc = !empty($album['Photo'][0]['id']) ? array('controller'=>'photos','action'=>'thumb',$album['Photo'][0]['id']) : "/images/no-photo.png";
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
	<div class='clear'></div>
	<? if(!empty($updates['photoAlbums'])) { ?>
	<div class='right_align'>
	<?= $this->Html->link("More photos ".$this->Html->g("chevron-right"), array('controller'=>"photo_albums"), array('class'=>'btn more right_align medium bold')); ?>
	</div>
	<? } ?>
	</div>
<? } ?>
</div>

