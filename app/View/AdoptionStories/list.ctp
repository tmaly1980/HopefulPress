<? if(empty($adoptionStories)) { ?>
	<div class='nodata'>No success stories yet</div>
<? } else { ?>
	<? foreach($adoptionStories as $story) { 
		$id = $story['AdoptionStory']['id'];
		$page_photo_id = $story['AdoptionStory']['page_photo_id'];
	?>
	<div class='row'>
		<div class='col-md-3'>
			<? if(!empty($page_photo_id)) { ?>
			<?= $this->Html->link($this->Html->image("/page_photos/page_photos/thumb/$page_photo_id/200x200/1",array('class'=>'maxwidth100p border')), "/adoption/stories/$id"); ?>
			<? } ?>
		</div>
		<div class='col-md-9'>
			<?= $this->Time->mondy($story['AdoptionStory']['modified']); ?>
			<?= $this->Html->link($story['AdoptionStory']['title'], array('action'=>'view',$id)); ?>
			<p>
				<?= $this->Text->truncate($story['AdoptionStory']['content']); ?>
				<?= $this->Html->link(" read more", array('action'=>'view',$id)); ?>
			</p>
		</div>
	</div>
	<? } ?>
<? } ?>

