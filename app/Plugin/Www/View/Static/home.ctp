<!-- variant=home.screenshot -->
<? $this->assign("flash_class", "flash_modal"); ?>
<? $this->assign("browser_title", "Helping rescues find homes for forgotten animals"); ?>
<? $this->set("meta_description", "Helping rescues find homes for forgotten animals - Headache-free &amp; professional rescue websites for just $10/mo"); ?>
<? $this->assign("title_container_class","gradientbg"); ?>

<div id='' class='row'>
	<div class='col-md-6'>
		<div  class='padding25'>
			<?= $this->Html->link($this->Html->image("/rescue/images/screenshot.png", array('style'=>'border: solid 5px #999;','class'=>'width100p shadow')),"/pages/features"); ?>
		</div>
	</div>
	<div class='col-md-6 padding25'>
		<?= $this->element("../Static/home_summary"); ?>
	</div>
</div>


<div class='row'>
<div class='col-md-8'>
	<?#= $this->element("Www.mission_intro"); ?>
	<div class='row'>
		<div class='col-md-8'>
			<?= $this->Html->image("/rescue/images/header-bg.png",array('class'=>'width100p')); ?>
		</div>
		<div class='col-md-4'>
			<?#= $this->requestAction("/blog/posts/recent", array('return')); ?>
		</div>
	</div>

</div>
<div class='col-md-4'>
	<?= $this->element("../Static/mailing_list"); ?>
</div>
</div>
