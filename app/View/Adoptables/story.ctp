	<div class='bordertop row'>
		<div class='col-md-3 padding10'>
			<? if($photo_id = $adoptable['SuccessStoryPhoto']['id']) { 
				$image = $this->Html->image(array('controller'=>'success_story_photos','action'=>'thumb',$photo_id,'300x300'),array('class'=>'border maxwidth100p'));
			?>

				<? if(empty($inline)) { ?>
					<?= $this->Html->link($image, array('action'=>'view',$adoptable['Adoptable']['id'])); ?>
				<? } else { ?>
					<?= $this->Html->link($image, array('controller'=>'success_story_photos','action'=>'fullimage',$photo_id,'600x600'),array('class'=>'lightbox')); ?>
				<? } ?>
			<? } ?>
		</div>
		<div class='col-md-9 padding10'>
			<? if(empty($inline)) { ?>
			<h3><?= $this->Html->link($adoptable['Adoptable']['name'],array('action'=>'view',$adoptable['Adoptable']['id'])); ?></h3>
			<? } ?>
			<?= !empty($adoptable['Adoptable']['story_date']) ? $this->Time->mondy($adoptable['Adoptable']['story_date'])."<br/><br/>" : null; ?>

			<div class='italic'>
			<? if(!empty($adoptable['Owner']['city'])) { ?>
				<?= $adoptable['Owner']['city'] ?>,
			<? } ?>
			<? if(!empty($adoptable['Owner']['state'])) { ?>
				<?= $adoptable['Owner']['state'] ?>
			<? } ?>
			</div>

			<div class='medium'>
				<?php echo ($adoptable['Adoptable']['success_story']); ?>
			</div>

			<? if(empty($this->rescuename) && !empty($adoptable['Rescue']['id'])) { ?>
			<div class=''>
			Adoption made possible by <?= $this->Html->link($adoptable['Rescue']['title'], array('controller'=>'rescues','action'=>'view',$adoptable['Rescue']['hostname'])); ?>, <?= $adoptable['Rescue']['city'] ?>, <?= $adoptable['Rescue']['state'] ?>
			</div>
			<? } ?>
			<div class='clear'></div>
		</div>
	</div>

