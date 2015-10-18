<? $carousel = (!empty($type) && $type == 'carousel'); ?>
<? #if(!empty($adoptables) || $this->Html->me()) { ?>
<div class='widget minwidth250'>
<h3 class=''><?= $this->Html->link("Current Adoptables", array('controller'=>'adoptables','action'=>'index')); ?></h3>
<div>
<? if(empty($adoptables)) { ?>
	<div class='nodata'>
		No available adoptables listed
	</div>
	<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->add("Add adoptables", array('user'=>1,'controller'=>'adoptables','action'=>'add'),array('class'=>'')); ?>
	<? } ?>
<? } else { ?>
<div id='adoptables' class='<?= !empty($carousel) ? "carousel slide" : "" ?>' <?= !empty($carousel) ? 'data-ride="carousel" data-wrap="true"' : "" ?> >

<div class='<?= !empty($carousel) ? "carousel-inner" : "" ?>' role="listbox">
<? for($i  = 0;  $i < count($adoptables); $i++) { 
	$adoptable = $adoptables[$i]; 
	$imgid = !empty($adoptable['Adoptable']['adoptable_photo_id']) ? $adoptable['Adoptable']['adoptable_photo_id'] : null;
	if(empty($imgid) && !empty($adoptable['Photos'][0]['id']))
	{
		$imgid = $adoptable['Photos'][0]['id'];
	}
?>
<div class='item <?= empty($i) ? "active" : "" ?> <?= $carousel ? "" : "left" ?> width200 maxheight325 center_align <?= empty($carousel) ? "padding25":""?>'>
	<div class='maxheight225'>
	<?= $this->Html->link($this->Html->image(!empty($imgid)?array('controller'=>'adoptable_photos','action'=>'thumb',$imgid,'200x200',1,'rescue'=>$adoptable['Rescue']['hostname']):"/images/nophoto.png", array('class'=>'border')), 
		array('action'=>'view',$adoptable['Adoptable']['id'],'rescue'=>$adoptable['Rescue']['hostname'])); ?> 
	</div>
	<div class='minheight75'>
		<?= $this->Html->link($adoptable['Adoptable']['name'], array('action'=>'view',$adoptable['Adoptable']['id'],'rescue'=>$adoptable['Rescue']['hostname']), array('class'=>'bold medium')); ?>
		<br/>
		<?= $this->element("../Adoptables/item_stats", array('adoptable'=>$adoptable)); ?>
		<!-- sanctuaries might want something else other than stats -->
	</div>
</div>
<? } ?>
</div>

<? if(!empty($carousel)) { ?>
      <a class="left carousel-control darkgrey" href="#adoptables" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control darkgrey" href="#adoptables" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
<script>
$(document).ready(function() {
	$('.carousel-inner').swipe({
		swipeLeft: function(event, direction, distance, duration)
		{
			$(this).parent().carousel('prev');
		},
		swipeRight: function(event, direction, distance, duration)
		{
			$(this).parent().carousel('next');
		},
		threshold: 0
	});
});
</script>
<? } ?>

<style>
.carousel .carousel-control
{
	visibility: hidden;
}

.carousel:hover .carousel-control
{
	visibility: visible;
}
</style>

<div class='clear'></div>



<div class='right_align margintop10'>
	<?= $this->Html->link("More adoptables".$this->Html->g("chevron-right"), array('controller'=>'adoptables'),array('class'=>'btn')); ?>
</div>


</div>
<? } ?>

</div>
</div>
<? #} ?>
