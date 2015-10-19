<!-- variant=home.screenshot -->
<? $this->assign("flash_class", "flash_modal"); ?>
<? $this->assign("browser_title", "Promote your rescue with a professional image"); ?>
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

<hr/>


<div class='row'>
<div class='col-md-8'>
	<?#= $this->element("Www.mission_intro"); ?>
	<div class='row'>
			<div class='col-md-4'>
				<div class='minheight225'>
					<?= $this->Html->link($this->Html->image("/rescue/images/features/thumbs/adoptable.png",array('class'=>'border width100p padding5')), "/features"); ?>
				</div>
				<h3><?= $this->Html->link("List adoptables with links to adopt and sponsor online", "/features"); ?></h3>
			</div>
			<div class='col-md-4'>
				<div class='minheight225'>
				<?= $this->Html->link($this->Html->image("/rescue/images/features/thumbs/volunteer.png",array('class'=>'border width100p padding5')), "/features"); ?>
				</div>
				<h3><?= $this->Html->link("Let volunteers update the website and apply online", "/features"); ?></h3>
			</div>
			<div class='col-md-4'>
				<div class='minheight225'>
				<?= $this->Html->link($this->Html->image("/rescue/images/features/thumbs/mobile.png",array('class'=>'border width100p padding5')), "/features"); ?>
				</div>
				<h3><?= $this->Html->link("View on mobile devices", "/features"); ?></h3>
			</div>
	</div>

	<hr/>

	<div class='center_align'>
		<?= $this->Html->link("View all Features ".$this->Html->g("chevron-right"), "/pages/features",array('class'=>'btn-lg btn btn-primary')); ?>
	</div>

</div>
<div class='col-md-4'>
	<?= $this->element("../Static/mailing_list"); ?>
</div>
</div>
