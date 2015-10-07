<? $this->start("post_title_header");?>
	<small class='bg-primary bold padding5 inline-block'>AVAILABLE</small>
<? $this->end("post_title_header");?>
<? $this->start("title_controls");?>
	<?= $this->Html->back("View all adoptables", "/mockup/adopt"); ?>
<? $this->end("title_controls");?>

<? $this->start("post_details_content");?>
		<?= $this->Html->link("Adopt Me", "/mockup/adopt/form", array('class'=>'dialog controls btn btn-primary btn-lg bold')); ?>
		<?= $this->Html->link("Sponsor Me", "/mockup/sponsor/form", array('class'=>'dialog controls btn btn-warning btn-lg')); ?>
		<?= $this->Html->link("Foster Me", "/mockup/foster/form", array('class'=>'dialog controls btn btn-success btn-lg')); ?>
<? $this->end("post_details_content");?>

<?= $this->element("../Mockup/adopt/details"); ?>

<h3>More Pictures</h3>
<div class='row'>
	<? for($i = 0; $i < 6; $i++) { ?>
	<div class='col-md-3'>
		<?= $this->Html->link($this->Html->image("/rescue/images/dog{$i}.jpg",array('class'=>'width100p')), "/rescue/images/dog{$i}.jpg",array('class'=>'lightbox')); ?>
	</div>
	<? } ?>
</div>


<h3>Videos</h3>
<div>
	<iframe src="http://www.youtube.com/embed/2UB8qgXmxA4?wmode=transparent" width="600" height="355" frameborder="0" webkitallowfullscreen="" allowfullscreen=""></iframe>
</div>




</div>

