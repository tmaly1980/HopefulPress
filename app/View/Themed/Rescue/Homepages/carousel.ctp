<!-- move to html plugin -->
<div id='carousel' class='carousel slide marginbottom20' data-ride="carousel" data-wrap="true">
<!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="/rescue/images/carousel1.jpg" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
	    	<div class='carousel-caption-bg'></div>
              	<h1>Meet Joey</h1>
		<p>He's cute, furry, and in need of love!</p>
		<?= $this->Html->link("Learn more", "/mockup/adopt/view",array('class'=>'btn btn-primary controls')); ?>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="second-slide" src="/rescue/images/carousel2.jpg" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
	    	<div class='carousel-caption-bg'></div>
              	<h1>Cola, Mary, and Juliet</h1>
		<p>Curious, tough, and mopey. This trio takes the cake.
		<?= $this->Html->link("Learn more", "/mockup/adopt/view",array('class'=>'btn btn-primary controls')); ?>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="/rescue/images/carousel3.jpg" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
	    	<div class='carousel-caption-bg'></div>
              	<h1>Janice loves to play!</h1>
		<p>A funny gal, she will chase anything you throw at her!</p>
		<?= $this->Html->link("Learn more", "/mockup/adopt/view",array('class'=>'btn btn-primary controls')); ?>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->

<style>
.carousel-caption > *
{
	position: relative;
	z-index: 1;
}
.carousel-caption > .carousel-caption-bg
{
	z-index: 0;
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background-color: white;
	opacity: 0.25;
}
</style>
