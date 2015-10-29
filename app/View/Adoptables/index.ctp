<? $animal = "adoptable" ; ?>
<? $this->assign("page_title", "Find Your Next Family Member"); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->me() && (!empty($rescuename) || ($rescuename = $this->Html->user("Rescue.hostname")))) { ?>
	<?= $this->Html->add("Add an $animal", array('user'=>1,'action'=>'edit')); ?>
<? } ?>
<? $this->end("title_controls"); ?>
<? if(!empty($adoptables)) { ?>
<div class='italic'><?= count($adoptables) ?> adoptable<?= count($adoptables) > 1 ? "s":"" ?> available</div>
<br/>
<? } ?>
<?= $this->element("../Adoptables/list"); ?>

<? if(!empty($adoptionStories)) { ?>
<hr/>
<h3 id='success'>Success Stories</h3>
<?= $this->element("../Adoptables/stories"); ?>
<div class='right_align'>
	<?= $this->Html->link("View all success stories", array('action'=>'stories'),array('class'=>'btn btn-default ')); ?>
</div>
<? } ?>
