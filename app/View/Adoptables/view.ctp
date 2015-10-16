<? $animals = "adoptables" ?>
<? $id = $adoptable['Adoptable']['id']; ?>
<? $this->start("post_title_header");?>
<?
$title_stats = array();
$title_keys = array('name','breed','gender','birthdate');# NOT SURE? ,'child_friendly','cat_friendly','dog_friendly');
foreach($title_keys as $key)
{
	if(!empty($adoptable['Adoptable'][$key]))
	{
		$value = $adoptable['Adoptable'][$key];
		if($key == 'birthdate')
		{
			$value = $this->Time->age($value);
		} else if ($key == 'gender') {
			$value = substr($value,0,1);
		} else if (is_numeric($value)) { 
			$value = Inflector::humanize($key);
		}
		$title_stats[] = $value;
	}
}

if(!empty($adoptable['Rescue']['city']) || !empty($adoptable['Rescue']['state']))
{
	$title_stats[] = join(", ", array_filter(array($adoptable['Rescue']['city'],$adoptable['Rescue']['state'])));
}
$title_string = join(" | ", $title_stats);
$this->assign("browser_title", $title_string);

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
<? $this->start("title_controls");?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Update", array('user'=>1,'action'=>'edit','id'=>$id,'rescue'=>$adoptable['Rescue']['hostname'])); ?>
<? } ?>
<? $this->end("title_controls");?>
<? $this->start("subtitle_nav"); ?>
	<div class='right'>
		<?= $this->Share->share(true); ?>
	</div>
	
	<? /* if(!empty($this->request->params['prefix'])) { ?>
		<?= $this->Html->back("Search Database", array('action'=>'search')); ?>
	<? } else { */ ?>
	<? if($status == 'Adopted' && !empty($adoptable['Adoptable']['success_story'])) { ?>
		<?= $this->Html->back("View all happy tails", array('action'=>'stories','rescue'=>$rescuename)); ?>
	<? } else { ?>
		<?= $this->Html->back("View all $animals", array('action'=>'index','rescue'=>$rescuename)); ?>
	<? } ?>
	<? # } # XXX TODO 'back to search' - w/session saved search criteria... ?>
<? $this->end("subtitle_nav"); ?>


<? $this->start("post_details_content");?>
<div>
	<? if(empty($rescuename)) { # Not on rescue page ?>
	<div class='border rounded padding25 margin25'>
		<h4>Courtesy of</h4>
		<?= $this->Html->link($adoptable['Rescue']['title'], array('controller'=>'rescues','action'=>'view','rescue'=>$adoptable['Rescue']['hostname'])); ?>
		<br/>
		<? if(!empty($adoptable['Rescue']['city'])) { ?>
		Location: <?= join(", ", array_filter(array($adoptable['Rescue']['city'], $adoptable['Rescue']['state']))); ?>
		<? } else if(!empty($adoptable['Rescue']['zip_code'])) { ?>
			<!-- XXX DETERMINE CITY FROM ZIP CODE -->
		<? } ?>

		<? # XXX CALCULATE DISTANCE FROM SEARCH  CRITERIA OR GPS ?>
	</div>
	<? } ?>
</div>
<div align='center'>
	<? if($status == 'Available') { ?>
		<?= $this->Html->link("Adopt Me", array('action'=>'adopt','id'=>$id), array('class'=>'controls btn btn-primary btn-lg bold')); ?>
		<? if(!empty($adoptable['Adoptable']['enable_sponsorship'])) { ?>
		&nbsp;
		&nbsp;
			<?= $this->Html->link("Sponsor Me", array('action'=>'sponsor','id'=>$id), array('class'=>'controls btn btn-warning btn-lg')); ?>
		<? } ?>
		<?#= $this->Html->link("Foster Me", "/mockup/adoptables/foster/$id", array('class'=>'dialog controls btn btn-success btn-lg')); ?>
	<? } ?>
</div>

		<br/>
<? $this->end("post_details_content");?>

<?= $this->element("../Adoptables/details"); ?>

<hr/>
<? if($adoptable['Adoptable']['status'] == 'Adopted' && (!empty($adoptable['Adoptable']['success_story']))) { ?>
<h3 class="margintop25">Happy Tail Story</h3>
<div class=''>
	<?= $this->element("../Adoptables/story",array('inline'=>1)); ?>
</div>
<? } ?>
<hr/>

<? if(!empty($adoptable['Photos'])) { ?>
<h3>More Pictures</h3>
<div class='clear'></div>
<?= $this->element("../AdoptablePhotos/list"); ?>
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

