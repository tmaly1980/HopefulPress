<? $id = !empty($adoptionPage["AdoptionPage"]["id"]) ? $adoptionPage["AdoptionPage"]["id"] : ""; ?>
<? $this->assign("page_title", $adoptionPage['AdoptionPage']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("left_admin_controls"); ?>
	<? if(!empty($adoptionPage['Parent']['idurl'])) { ?>
		<?= $this->Html->back(null, array("action"=>"view",$adoptionPage['Parent']['idurl'])); ?>
	<? } else { ?>
		<?= $this->Html->back(null, array("action"=>"index")); ?>
	<? } ?>
<? $this->end(); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Site->can_edit($adoptionPage['AdoptionPage'])) { ?>
	<?= $this->Html->blink("edit", "Edit AdoptionPage", array("user"=>1,"action"=>"edit",$id)); ?>
<? } ?>
<? $this->end(); ?>

<?#= $this->Publishable->publish(); ?>

<div class="adoptionPages view">
<div class=''>
	<div align='right'>
		<?= !empty($adoptionPage['AdoptionPage']['created']) ? $this->Time->monthdy($adoptionPage['AdoptionPage']['created']) : null; ?>
	</div>

	<?= $this->element("AdoptionPagePhotos.view"); ?>

	<div class='medium minheight300 padding25'>
		<?php echo ($adoptionPage['AdoptionPage']['content']); ?>
	</div>

	<div class='clear'></div>
</div>

<div class=''>
<? if($this->Site->can_edit($adoptionPage['AdoptionPage'])) { # By default, only owner can add sub adoptionPage - and then can re-assign ?>
	<div class='right'>
		<?= $this->Html->blink("add", "Add subpage", array("user"=>1,"action"=>"add",'parent_id'=>$id)); ?>
		<? if(count($adoptionPage['Subpage']) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>
	<h3>More information</h3>
	<? if(!empty($adoptionPage['Subpage'])) { ?>
		<?= $this->element("../AdoptionPages/list", array('adoptionPages'=>$adoptionPage['Subpage'])); ?>
	<? } ?>
</div>
<? if($this->Site->can_edit($adoptionPage['AdoptionPage'])) { ?>
<script>
$('#Subpage_sorter').sorter('.adoptionPagelist',{ prefix: "admin" });
</script>
<? } ?>

	

</div>

