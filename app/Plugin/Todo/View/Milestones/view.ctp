<? $id = !empty($milestone["Milestone"]["id"]) ? $milestone["Milestone"]["id"] : ""; ?>
<? $this->assign("page_title", "Milestone"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("edit", $milestone)) { ?>
	<?= $this->Html->edit("Edit Milestone", array("action"=>"edit",$id),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="milestones view">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<tr>
		<th><?php echo __('Title'); ?></th>
		<td>
			<?php echo h($milestone['Milestone']['title']); ?>
			&nbsp;
		</td>
</tr>
<tr>
</tr>
<tr>
		<th><?php echo __('Release'); ?></th>
		<td>
			<?php echo $this->Html->link($milestone['Release']['title'], array('controller' => 'releases', 'action' => 'view', $milestone['Release']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Description'); ?></th>
		<td>
			<?php echo h($milestone['Milestone']['description']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Start Date'); ?></th>
		<td>
			<?php echo h($milestone['Milestone']['start_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Finish Date'); ?></th>
		<td>
			<?php echo h($milestone['Milestone']['finish_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($milestone['Milestone']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($milestone['Milestone']['modified']); ?>
			&nbsp;
		</td>
</tr>
				</tbody>
			</table>
</div>

<div class="related row">
	<div class="col-md-12">
	<h3><?php echo __('Related Tasks'); ?></h3>
	<?php if (!empty($milestone['Task'])): ?>
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
		<th><?php echo __('Duedate'); ?></th>
		<th><?php echo __('Resolved'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"></th>
	</tr>
	<thead>
	<tbody>
	<?php foreach ($milestone['Task'] as $task): ?>
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
			<td><?php echo $task['duedate']; ?></td>
			<td><?php echo $task['resolved']; ?></td>
			<td><?php echo $task['created']; ?></td>
			<td><?php echo $task['modified']; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
<?php endif; ?>

	</div><!-- end col md 12 -->
</div>
