<? $id = !empty($this->request->data["Milestone"]["id"]) ? $this->request->data["Milestone"]["id"] : ""; ?>
<? $release_id = !empty($this->request->data["Milestone"]["release_id"]) ? $this->request->data["Milestone"]["release_id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Milestone" : "Add Milestone"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->back("View Milestone", array("action"=>"view",$id)); ?>
<? } else { ?>
	<?= $this->Html->back("All Milestones", array("action"=>"index")); ?>
<? } ?>
<? $this->end(); ?>

<div class="milestones form">

			<?php echo $this->Form->create('Milestone', array('role' => 'form')); ?>

					<?php echo $this->Form->input('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>
					<div id="Release">
						<?= $this->requestAction("/todo/releases/select/$release_id", array('return')); ?>
					</div>
					<?php echo $this->Form->input('title', array('class' => '', 'label'=>null, 'placeholder' => 'Title'));?>
					<?php echo $this->Form->input('description', array('class' => '', 'label'=>null, 'placeholder' => 'Description'));?>
					<?php echo $this->Form->input('status', array('options'=>$statuses,'class' => '', 'label'=>null, 'placeholder' => 'Status'));?>
					<?php echo $this->Form->input('start_date', array('type'=>'text','class' => 'datepicker', 'label'=>null, 'placeholder' => 'Start Date'));?>
					<?php echo $this->Form->input('finish_date', array('type'=>'text','class' => 'datepicker', 'label'=>null, 'placeholder' => 'Finish Date'));?>
				<table width='100%'><tr><td>
					<?= $this->Form->save(!$id?"Create Milestone":"Update Milestone"); ?>
				</td><td align="right">
				<? if(!empty($id) && $this->Site->can("delete")) { ?>
					<?= $this->Html->delete("Delete Milestone", array("action"=>"delete",$this->data["Milestone"]["id"]), array("confirm"=>"Are you sure you want to delete this milestone?")); ?>
				<? } ?>
				</td></tr></table>
			<?php echo $this->Form->end() ?>

			<div class='clear'></div>
			<script>
			</script>
</div>
