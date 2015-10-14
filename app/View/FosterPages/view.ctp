<? $id = !empty($fosterPage["FosterPage"]["id"]) ? $fosterPage["FosterPage"]["id"] : ""; ?>
<? $this->assign("page_title", $fosterPage['FosterPage']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("left_admin_controls"); ?>
	<? if(!empty($fosterPage['Parent']['idurl'])) { ?>
		<?= $this->Html->back(null, array("action"=>"view",$fosterPage['Parent']['idurl'])); ?>
	<? } else { ?>
		<?= $this->Html->back(null, array("action"=>"index")); ?>
	<? } ?>
<? $this->end(); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Site->can_edit($fosterPage['FosterPage'])) { ?>
	<?= $this->Html->blink("edit", "Edit Page", array("user"=>1,"action"=>"edit",$id)); ?>
<? } ?>
<? $this->end(); ?>

<?#= $this->Publishable->publish(); ?>

<div class="fosterPages view">
<div class=''>
	<div align='right'>
		<?= !empty($fosterPage['FosterPage']['created']) ? $this->Time->monthdy($fosterPage['FosterPage']['created']) : null; ?>
	</div>

	<?= $this->element("FosterPagePhotos.view"); ?>

	<div class='medium minheight300 padding25'>
		<?php echo ($fosterPage['FosterPage']['content']); ?>
	</div>

	<div class='clear'></div>
</div>

<div class=''>
<? if($this->Site->can_edit($fosterPage['FosterPage'])) { # By default, only owner can add sub fosterPage - and then can re-assign ?>
	<div class='right'>
		<?= $this->Html->blink("add", "Add subpage", array("user"=>1,"action"=>"add",'parent_id'=>$id)); ?>
		<? if(count($fosterPage['Subpage']) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>
	<h3>More information</h3>
	<? if(!empty($fosterPage['Subpage'])) { ?>
		<?= $this->element("../FosterPages/list", array('fosterPages'=>$fosterPage['Subpage'])); ?>
	<? } ?>
</div>
<? if($this->Site->can_edit($fosterPage['FosterPage'])) { ?>
<script>
$('#Subpage_sorter').sorter('.fosterPagelist',{ prefix: "admin" });
</script>
<? } ?>

	

</div>

