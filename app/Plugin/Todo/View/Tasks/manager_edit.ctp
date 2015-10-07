<? $id = !empty($this->request->data["Task"]["id"]) ? $this->request->data["Task"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Task" : "Add Task"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->back("View Task", array("action"=>"view",$id)); ?>
<? } else { ?>
	<?= $this->Html->back("All Tasks", array("action"=>"index")); ?>
<? } ?>
<? $this->end(); ?>

<div class="tasks form">

			<?php echo $this->Form->create('Task', array('role' => 'form')); ?>

					<?php echo $this->Form->input('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>
					<?php echo $this->Form->input('parent_id', array('class' => '', 'label'=>null, 'placeholder' => 'Parent Id'));?>
					<?php echo $this->Form->input('milestone_id', array('class' => '', 'label'=>null, 'placeholder' => 'Milestone Id'));?>
					<?php echo $this->Form->input('release_id', array('class' => '', 'label'=>null, 'placeholder' => 'Release Id'));?>
					<?php echo $this->Form->input('title', array('class' => '', 'label'=>null, 'placeholder' => 'Title'));?>
					<?php echo $this->Form->input('status', array('class' => '', 'label'=>null, 'placeholder' => 'Status'));?>
					<?php echo $this->Form->input('priority', array('class' => '', 'label'=>null, 'placeholder' => 'Priority'));?>
					<?php echo $this->Form->input('type', array('class' => '', 'label'=>null, 'placeholder' => 'Type'));?>
					<?php echo $this->Form->input('description', array('class' => '', 'label'=>null, 'placeholder' => 'Description'));?>
					<?php echo $this->Form->input('due_date', array('class' => '', 'label'=>null, 'placeholder' => 'Due Date'));?>
					<?php echo $this->Form->input('resolved', array('class' => '', 'label'=>null, 'placeholder' => 'Resolved'));?>
				<table width='100%'><tr><td>
					<?= $this->Form->save(!$id?"Create Task":"Update Task"); ?>
				</td><td align="right">
				<? if(!empty($id) && $this->Site->can("delete")) { ?>
					<?= $this->Html->delete("Delete Task", array("action"=>"delete",$this->data["Task"]["id"]), array("confirm"=>"Are you sure you want to delete this task?")); ?>
				<? } ?>
				</td></tr></table>
			<?php echo $this->Form->end() ?>

			<div class='clear'></div>
			<script>
			</script>
</div>
