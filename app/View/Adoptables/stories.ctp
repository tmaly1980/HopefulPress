<? foreach($adoptable['AdoptionStory'] as $story) { ?>
	<div class='borderbottom'>
			<? $story['PagePhoto']['align'] = 'left'; # FORCE ?>
			<?= $this->element("PagePhotos.view",array('pagePhoto'=>$story['PagePhoto'])); # pass photo as var since multiple per page  ?>
			<?= $this->Time->mondy($story['created']) ?>
			<h3>
				<?= $story['title']; ?>
			</h3>

			<div class='medium'>
				<?php echo ($story['content']); ?>
				<? if($this->Site->can_edit()) { ?>
					<?= $this->Html->edit("Edit", array("user"=>1,"action"=>"edit_story",$story['id']),array('class'=>'btn-xs btn-warning')); ?>
				<? } ?>
			</div>
			<div class='clear'></div>
	</div>
<? } ?>

