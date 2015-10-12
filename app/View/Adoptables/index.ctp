<? $animal = "adoptable" ; ?>
<? $this->assign("page_title", "Find your next companion animal"); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->me() && (!empty($rescuename) || ($rescuename = $this->Html->user("Rescue.hostname")))) { ?>
	<?= $this->Html->add("Add an $animal", array('user'=>1,'action'=>'edit','rescue'=>$rescuename)); ?>
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
	<?= $this->Html->link("View all success stories", "/adoption/stories",array('class'=>'btn btn-default ')); ?>
</div>
<? } ?>
