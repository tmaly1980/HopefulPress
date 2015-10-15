<? $id = !empty($adoptionPage["AdoptionPage"]["id"]) ? $adoptionPage["AdoptionPage"]["id"] : ""; ?>
<? $this->assign("page_title", $adoptionPage['AdoptionPage']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back('Adoption Info', array("action"=>"index")); ?>
<? if($this->Html->can_edit($adoptionPage['AdoptionPage'])) { ?>
	<?= $this->Html->edit("Edit Page", array("user"=>1,"action"=>"edit",$id)); ?>
<? } ?>
<? $this->end(); ?>

<?#= $this->Publishable->publish(); ?>

<div class="adoptionPages view">
<div class=''>
	<div align='right'>
		<?= !empty($adoptionPage['AdoptionPage']['created']) ? $this->Time->monthdy($adoptionPage['AdoptionPage']['created']) : null; ?>
	</div>

	<?= $this->element("PagePhotos.view"); ?>

	<div class='medium minheight300 padding25'>
		<?php echo ($adoptionPage['AdoptionPage']['content']); ?>
	</div>

	<div class='clear'></div>
</div>

	

</div>

