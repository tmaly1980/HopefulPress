<? if($this->request->action == 'stories') { $this->assign("page_title", "Happy Tails"); } ?>
<div class='index'>
<? if(empty($adoptionStories)) { ?>
<div class='nodata'>No adoption stories yet.</div>
<? } else { ?>
<? foreach($adoptionStories as $adoptable) { ?>
	<?= $this->element("../Adoptables/story",array('adoptable'=>$adoptable)); ?>
<? } ?>
<? } ?>
</div>
