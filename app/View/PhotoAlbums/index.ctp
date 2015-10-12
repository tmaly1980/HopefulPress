<? $pid = null;#$this->Admin->project('id'); ?>
<? #$project_title = $this->Admin->project('title'); ?>
<? $this->assign("page_title", "Photos".(!empty($project_title)? " For $project_title":"")); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
	<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->add("Add Photo Album", array("user"=>1,"action"=>"add")); ?>
	<? } ?>
<? $this->end(); ?>
<div class="photoAlbums index <?#= $this->Admin->fontsize('default'); ?>">

	<? if(empty($photoAlbums)) { ?>
		<div class='nodata'>There are no albums.</div>
	<? } else { ?>
		<? foreach($photoAlbums as $album) { ?>
		<div class='left margintop5 marginright15 height150 block'>
			<?
				$imgsrc = !empty($album['Photo'][0]['id']) ? array('controller'=>"photos",'action'=>"thumb",$album['Photo'][0]['id']) : "/images/no-photo.png";
				$img = $this->Html->image($imgsrc, array('class'=>'border')); 
				$title = !empty($album['PhotoAlbum']['title']) ? $album['PhotoAlbum']['title'] : "Untitled Album";
			?>
			<div align='center'>
			<?= $this->Html->link($img, array('action'=>'view',$album['PhotoAlbum']['idurl'],'project_id'=>$pid), array()); ?>
			</div>
			<?= $this->Html->link($title, array('action'=>'view',$album['PhotoAlbum']['idurl'],'project_id'=>$pid), array()); ?>
			<div class=''>
				<?= $this->Time->mondy($album['PhotoAlbum']['created']); ?> &ndash;
				<?= !empty($album['Photo']) ? count($album['Photo']) : "No" ?> photos
			</div>

		</div>
		<? } ?>
	<? } ?>

	<?= $this->element("Core.pager"); ?>
</div>
