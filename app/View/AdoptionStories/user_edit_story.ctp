<? $this->start("title_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->blink("back", "View Success Story", array("action"=>"view",$id)); ?>
<? } else { ?>
	<?= $this->Html->blink("back", "All Success Stories", array("action"=>"index")); ?>
<? } ?>
<? $this->end(); ?>

<?= $this->element("Rescue.../AdoptionStories/edit"); ?>

</div>
