<? $id = !empty($this->request->data["Module"]["id"]) ? $this->request->data["Module"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Module" : "Add Module"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->back("View Module", array("action"=>"view",$id)); ?>
<? } else { ?>
	<?= $this->Html->back("All Modules", array("action"=>"index")); ?>
<? } ?>
<? $this->end(); ?>

<div class="modules form">

			<?php echo $this->Form->create('Module', array('role' => 'form')); ?>

					<?php echo $this->Form->input('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>
					<?php echo $this->Form->input('title', array('class' => '', 'label'=>null, 'placeholder' => 'Title'));?>
					<?php echo $this->Form->input('description', array('class' => '', 'label'=>null, 'placeholder' => 'Description'));?>
				<table width='100%'><tr><td>
					<?= $this->Form->save(!$id?"Create Module":"Update Module"); ?>
				</td><td align="right">
				<? if(!empty($id) && $this->Site->can("delete")) { ?>
					<?= $this->Html->delete("Delete Module", array("action"=>"delete",$this->data["Module"]["id"]), array("confirm"=>"Are you sure you want to delete this module?")); ?>
				<? } ?>
				</td></tr></table>
			<?php echo $this->Form->end() ?>

			<div class='clear'></div>
			<script>
			</script>
</div>
