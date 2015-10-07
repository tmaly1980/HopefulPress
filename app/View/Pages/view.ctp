<? $id = !empty($page["Page"]["id"]) ? $page["Page"]["id"] : ""; ?>
<? $this->assign("page_title", $page['Page']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("left_admin_controls"); ?>
	<? if(!empty($page['Parent']['idurl'])) { ?>
		<?= $this->Html->back(null, array("action"=>"view",$page['Parent']['idurl'])); ?>
	<? } else { ?>
		<?= $this->Html->back(null, array("action"=>"index")); ?>
	<? } ?>
<? $this->end(); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Site->can_edit($page['Page'])) { ?>
	<?= $this->Html->blink("edit", "Edit Page", array("user"=>1,"action"=>"edit",$id)); ?>
<? } ?>
<? $this->end(); ?>

<?#= $this->Publishable->publish(); ?>

<div class="pages view">
<div class=''>
	<div align='right'>
		<?= !empty($page['Page']['created']) ? $this->Time->monthdy($page['Page']['created']) : null; ?>
	</div>

	<?= $this->element("PagePhotos.view"); ?>

	<div class='medium minheight300 padding25'>
		<?php echo ($page['Page']['content']); ?>
	</div>

	<div class='clear'></div>
</div>

<div class=''>
<? if($this->Site->can_edit($page['Page'])) { # By default, only owner can add sub page - and then can re-assign ?>
	<div class='right'>
		<?= $this->Html->blink("add", "Add subpage", array("user"=>1,"action"=>"add",'parent_id'=>$id)); ?>
		<? if(count($page['Subpage']) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>
	<h3>More information</h3>
	<? if(!empty($page['Subpage'])) { ?>
		<?= $this->element("../Pages/list", array('pages'=>$page['Subpage'])); ?>
	<? } ?>
</div>
<? if($this->Site->can_edit($page['Page'])) { ?>
<script>
$('#Subpage_sorter').sorter('.pagelist',{ prefix: "admin" });
</script>
<? } ?>

	

</div>

