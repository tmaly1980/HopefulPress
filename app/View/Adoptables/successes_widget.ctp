<? if(!empty($successes) || $this->Html->me()) { ?>
<div class='widget minwidth250'>
<h3 class=''><?= $this->Html->link("Happy Tails", array('controller'=>'adoptables','action'=>'stories')); ?></h3>
<div class='index'>
<? if(empty($successes)) { ?>
	<div class='nodata'>
		No success stories yet.
	</div>
<? } else { ?>
<? for($i  = 0;  $i < count($successes); $i++) { 
	$story = $successes[$i]; 
	$imgurl = "/images/nophoto.png";
	if(!empty($story['Adoptable']['success_story_photo_id']))
	{
		$imgurl = array('controller'=>'success_story_photos','action'=>'thumb',$story['Adoptable']['success_story_photo_id'],'200x200',1);
	}
	else if(!empty($story['Adoptable']['adoptable_photo_id']))
	{
		$imgurl = array('controller'=>'adoptable_photos','action'=>'thumb',$story['Adoptable']['adoptable_photo_id'],'200x200',1);
	}
	else if(!empty($story['Photos'][0]['id']))
	{
		$imgurl = array('controller'=>'adoptable_photos','action'=>'thumb',$story['Photos'][0]['id'],'200x200',1);
	}
?>
<div class='item width200 maxheight300 center_align padding25'>
	<div class='maxheight225'>
	<?= $this->Html->link($this->Html->image($imgurl, array('class'=>'border')), 
		array('action'=>'view',$story['Adoptable']['id'],'rescue'=>$story['Rescue']['hostname'])); ?> 
	</div>
	<div class='minheight75'>
		<?= $this->Html->link($story['Adoptable']['name'], array('action'=>'view',$story['Adoptable']['id']), array('class'=>'bold medium')); ?>
		<br/>
		<? if(!empty($story['Adoptable']['story_date'])) { ?>
			<?= $this->Time->mondy($story['Adoptable']['story_date']); ?>
		<? } else if(!empty($story['Adoptable']['adopted_date'])) { ?>
			<?= $this->Time->mondy($story['Adoptable']['adopted_date']); ?>
		<? } ?>
		<div class='italic'>
		<? if(!empty($story['Owner']['city'])) { ?>
			<?= $story['Owner']['city'] ?>,
		<? } ?>
		<? if(!empty($story['Owner']['state'])) { ?>
			<?= $story['Owner']['state'] ?>
		<? } ?>
		</div>
	</div>
</div>
<? } ?>

<? } ?>

<div class='clear'></div>



<div class='right_align margintop10'>
	<?= $this->Html->link("More happy tails".$this->Html->g("chevron-right"), array('controller'=>'adoptables','action'=>'stories'),array('class'=>'btn')); ?>
</div>


</div>
<? } ?>

</div>
