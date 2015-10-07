<? $this->start("post_title_header");?>
	<small class='bg-success bold padding5 inline-block'>ADOPTED</small>
<? $this->end("post_title_header");?>
<? $this->start("title_controls");?>
	<?= $this->Html->back("View all successes", "/mockup/adopt#success"); ?>
<? $this->end("title_controls");?>
<? $this->start("post_details_content");?>
	<div>
		<b>Date Adopted:</b> 
		09/24/2014
	</div>
<? $this->end(); ?>

<?= $this->element("../Mockup/adopt/details"); ?>

<h3>Updates:</h3>
<div class='margintop25'>
	<h4 class='bold'>12/24/2014 (3 months)</h4>
	<p>Mr. Wiggles is in his new home, happier than ever! See pics!</p>
	<div class='row'>
	<? for($i = 3; $i < 7; $i++) { ?>
	<div class='col-md-3'>
		<?= $this->Html->link($this->Html->image("/rescue/images/dog{$i}.jpg",array('class'=>'width100p')), "/rescue/images/dog{$i}.jpg",array('class'=>'lightbox')); ?>
	</div>
	<? } ?>
	</div>
</div>
<div class='margintop25'>
	<h4 class='bold'>09/24/2014</h4>
	<p>Mr. Wiggles met Clarissa and David, who just adore him! Watch the video!

	<iframe src="http://www.youtube.com/embed/2UB8qgXmxA4?wmode=transparent" width="600" height="355" frameborder="0" webkitallowfullscreen="" allowfullscreen=""></iframe>
</div>
	<!-- blog-style, rich editor -->
<? $this->end("post_details_content");?>
