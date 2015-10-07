<? $id = !empty($module["Module"]["id"]) ? $module["Module"]["id"] : ""; ?>
<? $this->assign("page_title", "Module"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("edit", $module)) { ?>
	<?= $this->Html->edit("Edit Module", array("action"=>"edit",$id),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="modules view">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<tr>
		<th><?php echo __('Title'); ?></th>
		<td>
			<?php echo h($module['Module']['title']); ?>
			&nbsp;
		</td>
</tr>
<tr>
</tr>
<tr>
		<th><?php echo __('Description'); ?></th>
		<td>
			<?php echo h($module['Module']['description']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($module['Module']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($module['Module']['modified']); ?>
			&nbsp;
		</td>
</tr>
				</tbody>
			</table>
</div>

<div class="related row">
	<div class="col-md-12">
	<h3><?php echo __('Related Tasks'); ?></h3>
	<?php if (!empty($module['Task'])): ?>
	<table cellpadding = "0" cellspacing = "0" class="table table-striped">
	<thead>
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Parent Id'); ?></th>
		<th><?php echo __('Milestone Id'); ?></th>
		<th><?php echo __('Release Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Priority'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Due Date'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Deferred Date'); ?></th>
		<th><?php echo __('In Progress Date'); ?></th>
		<th><?php echo __('Needs Testing Date'); ?></th>
		<th><?php echo __('Closed Date'); ?></th>
		<th><?php echo __('Invalid Date'); ?></th>
		<th><?php echo __('Module Id'); ?></th>
		<th><?php echo __('Impact'); ?></th>
		<th class="actions"></th>
	</tr>
	<thead>
	<tbody>
	<?php foreach ($module['Task'] as $task): ?>
		<tr>
			<td><?php echo $task['id']; ?></td>
			<td><?php echo $task['parent_id']; ?></td>
			<td><?php echo $task['milestone_id']; ?></td>
			<td><?php echo $task['release_id']; ?></td>
			<td><?php echo $task['title']; ?></td>
			<td><?php echo $task['status']; ?></td>
			<td><?php echo $task['priority']; ?></td>
			<td><?php echo $task['type']; ?></td>
			<td><?php echo $task['description']; ?></td>
			<td><?php echo $task['due_date']; ?></td>
			<td><?php echo $task['created']; ?></td>
			<td><?php echo $task['modified']; ?></td>
			<td><?php echo $task['deferred_date']; ?></td>
			<td><?php echo $task['in_progress_date']; ?></td>
			<td><?php echo $task['needs_testing_date']; ?></td>
			<td><?php echo $task['closed_date']; ?></td>
			<td><?php echo $task['invalid_date']; ?></td>
			<td><?php echo $task['module_id']; ?></td>
			<td><?php echo $task['impact']; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
<?php endif; ?>

	</div><!-- end col md 12 -->
</div>
