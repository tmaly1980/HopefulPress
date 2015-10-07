<? $this->assign("page_title", "Mr. Wiggles"); ?>
<div class='view'>
<div class='row'>
	<div class='col-md-4'>
		<?= $this->Html->image("/rescue/images/dog3.jpg",array('class'=>'width100p')); ?>
	</div>
	<div class='col-md-8'>
		<p>Mr. Wiggles is a cuddly creature. Abandoned behind a supermarket, he quickly became a crowd pleaser.

		<div>
			<b>Age:</b> 6 weeks (born 12/12/15)
		</div>
		<div>
			<b>Breed:</b> Hound
		</div>
		<div>
			<b>Sex:</b> Male
		</div>


		<hr/>

		<?= $this->fetch("post_details_content"); ?>

	</div>
</div>

