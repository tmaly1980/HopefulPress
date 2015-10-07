<? $this->assign("page_title", "Tasks"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? #if($this->Site->can("add", "")) { ?>
	<?= $this->Html->add("Add Task", array("action"=>"edit"),array("class"=>"btn btn-success")); ?>
	<?= $this->Html->add("Add Milestone", array("controller"=>'milestones',"action"=>"edit"),array("class"=>"btn btn-default")); ?>
<? #} ?>
<? $this->end(); ?>
<?/*
<div>
<h3>Filter</h3>
<?= $this->Form->create("Task",array('url'=>array('action'=>'index'),'id'=>'FilterForm','method'=>'GET')); ?>
<div class='row'>
		<?= $this->Form->input("text",array('type'=>'text','div'=>'col-md-2')); ?>
		<?= $this->Form->input("module_id",array('empty'=>'','div'=>'col-md-2')); ?>
		<?#= $this->Form->input("release_id",array('empty'=>'','div'=>'col-md-2')); ?>
		<?= $this->Form->input("milestone_id",array('empty'=>'','div'=>'col-md-2')); ?>
		<?= $this->Form->input("type",array('empty'=>'','div'=>'col-md-2','options'=>$types)); ?>
		<?= $this->Form->input("priority",array('empty'=>'','div'=>'col-md-2','options'=>$priorities)); ?>
		<?= $this->Form->input("status",array('empty'=>'','div'=>'col-md-2','options'=>$statuses)); ?>
</div>
<div align='right'>
	<?= $this->Form->save("Search",array('cancel'=>false)); ?>
</div>
<?= $this->Form->end(); ?>
</div>
<script>
$('#FilterForm select, #FilterForm input').change(function() { 
	console.log("HELLO");
	$('#FilterForm').submit(); 
});
</script>
*/ ?>
<div class="tasks index">
	<?= $this->element("../Tasks/list",array('sorter'=>1)); ?>
	<?= $this->element("pager"); ?>

	<? if(!empty($milestones)) { ?>
	<table class='table'>
	<tr>
		<th>Milestone</th>
		<th>Release</th>
		<th>Status</th>
		<th>Start Date</th>
		<th>Finish Date</th>
	</tr>

	<? foreach($milestones as $milestone) { ?>
	<tr id='Milestone_<?= $milestone['Milestone']['id'] ?>' class='lightgreybg' style="border-top: solid 3px #666;">
		<td><?= $this->Html->link($milestone['Milestone']['title'], array('controller'=>'milestones','action'=>'edit',$milestone['Milestone']['id'])); ?>
		</td>
		<td> <?= $this->Html->link($milestone['Release']['title'],array('controller'=>'releases','action'=>'edit',$milestone['Release']['id']))  ?> </td>
		<td> <?= $milestone['Milestone']['status']  ?> </td>
		<td> <?= $milestone['Milestone']['start_date']  ?> </td>
		<td> <?= $milestone['Milestone']['finish_date']  ?> </td>
	</tr>
	<tr>
		<td colspan=5>
			<? if(empty($milestone['Task'])) { ?>
				<div class='nodata'>No (remaining) tasks</div>
			<? } else { ?>
				<?= $this->element("../Tasks/list", array('tasks'=>$milestone['Task'],'milestone'=>$milestone)); ?>
			<? } ?>
			<div>
				<?= $this->Html->add("Add task", array('controller'=>'tasks','action'=>'add','milestone_id'=>$milestone['Milestone']['id'],'release_id'=>$milestone['Milestone']['release_id']),array('class'=>'btn-xs')); ?>
			</div>
		</td>
	</tr>
	<? } ?>

	</table>
	<? } ?>


</div><!-- end containing of content -->
