	<div class='bordertop row'>
		<div class='col-md-3 padding10'>
		<?
			$imgurl = "/images/nophoto.png";
			if(!empty($adoptable['Adoptable']['success_story_photo_id']))
			{
				$imgurl = array('controller'=>'success_story_photos','action'=>'thumb',$adoptable['Adoptable']['success_story_photo_id'],'200x200',1);
			}
			else if(!empty($adoptable['Adoptable']['rescue_photo_id']))
			{
				$imgurl = array('controller'=>'adoptable_photos','action'=>'thumb',$adoptable['Adoptable']['rescue_photo_id'],'200x200',1);
			}
			else if(!empty($adoptable['Photos'][0]['id']))
			{
				$imgurl = array('controller'=>'adoptable_photos','action'=>'thumb',$adoptable['Photos'][0]['id'],'200x200',1);
			}
			$image = $this->Html->image($imgurl,array('class'=>'border maxwidth100p'));
			?>

				<? if(empty($inline)) { ?>
					<?= $this->Html->link($image, array('action'=>'view',$adoptable['Adoptable']['id'])); ?>
				<? } else { ?>
					<?= $this->Html->link($image, array('controller'=>'success_story_photos','action'=>'fullimage',$adoptable['Adoptable']['id'],'600x600'),array('class'=>'lightbox')); ?>
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

			<? /* # DOESNT SEEM TO WORK.... still shows up on any site. $this->rescuename empty? # if(empty($this->rescuename) && !empty($adoptable['Rescue']['id'])) { ?>
			<div class=''>
			Adoption made possible by <?= $this->Html->link($adoptable['Rescue']['title'], array('controller'=>'rescues','action'=>'view',$adoptable['Rescue']['hostname'])); ?>, <?= $adoptable['Rescue']['city'] ?>, <?= $adoptable['Rescue']['state'] ?>
			</div>
			<? } */ ?>
			<div class='clear'></div>
		</div>
	</div>

