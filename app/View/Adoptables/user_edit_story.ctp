<? if(empty($adoptable_id)) { 
	$adoptable_id = !empty($this->request->data['AdoptionStory']['adoptable_id']) ? $this->request->data['AdoptionStory']['adoptable_id'] : null; 
} ?>

<? $this->start("title_controls"); ?>
<? if(!empty($adoptable_id)) { ?>
	<?= $this->Html->blink("back", "Edit Adoptable", array("action"=>"edit",$adoptable_id)); ?>
<? } else { ?>
	<?= $this->Html->blink("back", "All Success Stories", array("controller"=>"adoption_stories","action"=>"index")); ?>
<? } ?>
<? $this->end(); ?>

<?= $this->element("Rescue.../AdoptionStories/edit"); ?>

</div>
