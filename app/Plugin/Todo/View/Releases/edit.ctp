<? $id = !empty($this->request->data["Release"]["id"]) ? $this->request->data["Release"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Release" : "Add Release"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->back("View Release", array("action"=>"view",$id)); ?>
<? } else { ?>
	<?= $this->Html->back("All Releases", array("action"=>"index")); ?>
<? } ?>
<? $this->end(); ?>

<div class="releases form">

			<?php echo $this->Form->create('Release', array('role' => 'form')); ?>

					<?php echo $this->Form->input('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>
					<?php echo $this->Form->input('title', array('class' => '', 'label'=>null, 'placeholder' => 'Title'));?>
					<?php echo $this->Form->input('description', array('class' => '', 'label'=>null, 'placeholder' => 'Description'));?>
					<?php echo $this->Form->input('launch_date', array('class' => 'datepicker', 'type'=>'text','label'=>null, 'placeholder' => 'Launch Date'));?>
				<table width='100%'><tr><td>
					<?= $this->Form->save(!$id?"Create Release":"Update Release"); ?>
				</td><td align="right">
				<? if(!empty($id) && $this->Site->can("delete")) { ?>
					<?= $this->Html->delete("Delete Release", array("action"=>"delete",$this->data["Release"]["id"]), array("confirm"=>"Are you sure you want to delete this release?")); ?>
				<? } ?>
				</td></tr></table>
			<?php echo $this->Form->end() ?>

			<div class='clear'></div>
			<script>
			</script>
</div>
