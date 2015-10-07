<? $id = !empty($this->request->data["Page"]["id"]) ? $this->request->data["Page"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Page" : "Add Page"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->blink("back", "View Page", array("action"=>"view",$id)); ?>
<? } else { ?>
	<?= $this->Html->blink("back", "All Pages", array("action"=>"index")); ?>
<? } ?>
<? $this->end(); ?>

<div class="pages form">

			<?php echo $this->Form->create('Page', array('role' => 'form')); ?>

					<?php echo $this->Form->input('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>
					<?php echo $this->Form->input('title', array('class' => '', 'label'=>null, 'placeholder' => 'Title'));?>
					<?php echo $this->Form->input('url', array('class' => '', 'label'=>null, 'placeholder' => 'Url'));?>
					<?php echo $this->Form->input('content', array('class' => '', 'label'=>null, 'placeholder' => 'Content'));?>
				<table width='100%'><tr><td>
					<?= $this->Form->save(!$id?"Create Page":"Update Page"); ?>
				</td><td align="right">
				<? if(!empty($id)) { ?>
					<?= $this->Html->delete("Delete Page", array("action"=>"delete",$this->data["Page"]["id"]), array("confirm"=>"Are you sure you want to delete this page?")); ?>
				<? } ?>
				</td></tr></table>
			<?php echo $this->Form->end() ?>

			<div class='clear'></div>
			<script>
			</script>
</div>
