<? $rescue = $this->Site->get("rescue_enabled"); ?>
<? $animals = $rescue ? "adoptables" : "animal profiles"; ?>
<? $id = $adoptable['Adoptable']['id']; ?>
<? if($rescue) { ?>
<? $this->start("post_title_header");?>
<?
$statusClasses = array(
	'Available'=>'btn-success',
	'Pending Adoption'=>'btn-warning',
	'Adopted'=>'btn-primary'
);
$status = $adoptable['Adoptable']['status'];
$statusClass = $statusClasses[$status];
?>
	<small class='white <?= $statusClass ?> padding5 inline-block'><?= strtoupper($adoptable['Adoptable']['status']); ?></small>
<? $this->end("post_title_header");?>
<? } ?>
<? $this->start("title_controls");?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Update", array('rescuer'=>1,'action'=>'edit','id'=>$id,'rescue'=>$rescuename)); ?>
<? } ?>
<? $this->end("title_controls");?>
<? $this->start("subtitle_nav"); ?>
	<div class='right'>
		<?= $this->Share->share(true); ?>
	</div>
	
	<? if(!empty($this->request->params['prefix'])) { ?>
		<?= $this->Html->back("Search Database", array('action'=>'search')); ?>
	<? } else { ?>
		<?= $this->Html->back("View all $animals", array('action'=>'index','rescue'=>$rescuename)); ?>
	<? } ?>
<? $this->end("subtitle_nav"); ?>


<? $this->start("post_details_content");?>
<div align='center'>
	<? if($rescue) { ?>
		<?= $this->Html->link("Adopt Me", "/rescue/adoptables/adopt/$id", array('class'=>'dialog controls btn btn-primary btn-lg bold')); ?>
	<? } ?>
		<? if(!$rescue || !empty($adoptable['Adoptable']['enable_sponsorship'])) { ?>
		&nbsp;
		&nbsp;
			<?= $this->Html->link("Sponsor Me", ($rescue?"/rescue/adoptables":"/sanctuary/animals")."/sponsor/$id", array('class'=>'controls btn btn-warning btn-lg')); ?>
		<? } ?>
		<?#= $this->Html->link("Foster Me", "/mockup/adoptables/foster/$id", array('class'=>'dialog controls btn btn-success btn-lg')); ?>
</div>

		<br/>
<? $this->end("post_details_content");?>

<?= $this->element("../Adoptables/details"); ?>

<hr/>

<? if(!empty($adoptable['Photos'])) { ?>
<h3>More Pictures</h3>
<div class='clear'></div>
<?= $this->element("../AdoptablePhotos/list"); ?>
<? } ?>

<? if($adoptable['Adoptable']['status'] == 'Adopted' && (!empty($adoptable['SuccessStory']) || $this->Html->can_edit())) { ?>
<h3>Success Story</h3>
<? if(empty($adoptable['SuccessStory']['id'])) { # Probably moved beelow... ?>
	<?= $this->Html->add("Add Success Story", array('rescuer'=>1,'action'=>'edit_success_story','id'=>$id,'rescue'=>$rescuename)); ?>
<? } else { ?>
	<?= $this->Html->edit("Edit Success Story", array('rescuer'=>1,'action'=>'edit_success_story','id'=>$id,'rescue'=>$rescuename)); ?>
	SUCCESS STORY TODO....
<? } ?>

<? } ?>

<? /* if($this->Html->me()) { ?>
<div class='right'>
	<?= $this->Html->add("Add a video", array('user'=>1,'controller'=>'adoptable_videos','action'=>'add','adoptable_id'=>$adoptable['Adoptable']['id'])); ?>
</div>
<? } */ ?>
<?/* 
<h3>Videos</h3>
<div>
       <iframe src="http://www.youtube.com/embed/2UB8qgXmxA4?wmode=transparent" width="600" height="355" frameborder="0" webkitallowfullscreen="" allowfullscreen=""></iframe>
</div>

<? if(!empty($adoptable['Video']) || $this->Html->me()) { ?>
<?#= $this->element("../AdoptableVideos/list"); ?>
<? } ?>

<? */ ?>




</div>

