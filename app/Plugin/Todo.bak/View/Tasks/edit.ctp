<? $id = !empty($this->request->data["Task"]["id"]) ? $this->request->data["Task"]["id"] : ""; ?>
<? $module_id = !empty($this->request->data["Task"]["module_id"]) ? $this->request->data["Task"]["module_id"] : ""; ?>
<? $milestone_id = !empty($this->request->data["Task"]["milestone_id"]) ? $this->request->data["Task"]["milestone_id"] : ""; ?>
<? $release_id = !empty($this->request->data["Task"]["release_id"]) ? $this->request->data["Task"]["release_id"] : ""; ?>
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
					<?php echo $this->Form->input('title', array('class' => '', 'label'=>null, 'placeholder' => 'Title'));?>
					<div class='row'>
					<div id="Module" class='col-md-3'>
						<?= $this->requestAction("/todo/modules/select/$module_id", array('return')); ?>
					</div>

					<div id="Release"  class='col-md-3'>
						<?= $this->requestAction("/todo/releases/select/$release_id", array('return')); ?>
					</div>
					<div id="Milestone"  class='col-md-3'>
						<?= $this->requestAction("/todo/milestones/select/$milestone_id", array('return')); ?>
					</div>

					<?php echo $this->Form->input('parent_id', array('class' => '', 'label'=>null, 'placeholder' => 'Parent Id','div'=>'col-md-3','empty'=>''));?>
					</div>
					<div class='row'>
					<?php echo $this->Form->input('type', array('class' => '', 'label'=>null, 'placeholder' => 'Type','options'=>$types,'div'=>'col-md-4'));?>
					<?php echo $this->Form->input('priority', array('default'=>'Normal','class' => '', 'label'=>'Priority/Severity', 'placeholder' => 'Priority','options'=>$priorities,'div'=>'col-md-4'));?>
					<?php echo $this->Form->input('status', array('class' => '', 'label'=>null, 'placeholder' => 'Status','options'=>$statuses,'div'=>'col-md-4'));?>
					</div>
					<div class='row'>
					<?php echo $this->Form->input('description', array('class' => '', 'label'=>null, 'placeholder' => 'Description','div'=>'col-md-6'));?>
					<?php echo $this->Form->input('impact', array('class' => '', 'label'=>null, 'placeholder' => 'Impact','div'=>'col-md-6'));?>
					</div>
					<div class='row'>
					<?php echo $this->Form->input('due_date', array('type'=>'text','class' => 'datepicker', 'label'=>null, 'placeholder' => 'Due Date','div'=>'col-md-6'));?>
					<?php #echo $this->Form->input('resolved', array('type'=>'text','class' => 'datepicker', 'label'=>null, 'placeholder' => 'Resolved','div'=>'col-md-6'));?>
					</div>
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
