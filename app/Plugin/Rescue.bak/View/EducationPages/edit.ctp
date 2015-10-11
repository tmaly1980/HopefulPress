<? $pid = Configure::read("project_id"); ?>
<?= $this->element("Core.js/editor"); ?>

<? $id = !empty($this->request->data["EducationPage"]["id"]) ? $this->request->data["EducationPage"]["id"] : ""; ?>
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

			<?php echo $this->Form->create('EducationPage', array('role' => 'form')); ?>

					<?php echo $this->Form->hidden('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>
					<?#= $this->Form->hidden("project_id"); ?>

				<div class='row'>

				<div class='col-md-6'>
					<?= $this->Form->title(); ?>

				</div>
				<div class='col-md-6'>
					<?= $this->element("PagePhotos.edit"); ?>
				</div>

				</div>

					<?= $this->Form->content(); ?>
				<table width='100%'><tr><td>
				<? if(!empty($id)) { ?>
					<?= $this->Html->delete("Delete Page", array("action"=>"delete",$this->data["EducationPage"]["id"]), array("confirm"=>"Are you sure you want to delete this page?")); ?>
				<? } ?>
				</td><td align="right">
					<?= $this->Form->save(!$id?"Create Page":"Update Page"); ?>
				</td></tr></table>
			<?php echo $this->Form->end() ?>

			<div class='clear'></div>
			<script>
			</script>
</div>
