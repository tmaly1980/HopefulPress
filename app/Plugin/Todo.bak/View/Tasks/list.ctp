<table cellpadding="0" cellspacing="0" class="table table-striped">
	<thead>
		<tr>
			<th><?php echo empty($sorter) ? "Title" : $this->Paginator->sort('title'); ?></th>
			<th><?php echo empty($sorter) ? "Module" : $this->Paginator->sort('module_id'); ?></th>
			<? if(empty($milestone)) { ?>
			<th><?php echo empty($sorter) ? "Milestone" : $this->Paginator->sort('milestone_id'); ?></th>
			<? } ?>
			<!--<th><?php echo empty($sorter) ? "Parent" : $this->Paginator->sort('parent_id'); ?></th>-->
			<!--
			<th><?php echo empty($sorter) ? "Release" : $this->Paginator->sort('release_id'); ?></th>
			-->
			<th><?php echo empty($sorter) ? "Type" : $this->Paginator->sort('type'); ?></th>
			<th><?php echo empty($sorter) ? "Status" : $this->Paginator->sort('status'); ?></th>
			<th><?php echo empty($sorter) ? "Priority/Severity" : $this->Paginator->sort('priority','Priority/Severity'); ?></th>
			<!--<th><?php echo empty($sorter) ? "Description" : $this->Paginator->sort('description'); ?></th>-->
			<th><?php echo empty($sorter) ? "Created" : $this->Paginator->sort('created'); ?></th>
			<th><?php echo empty($sorter) ? "Due" : $this->Paginator->sort('due_date'); ?></th>
			<!--<th><?php echo empty($sorter) ? "Closed" : $this->Paginator->sort('closed_date'); ?></th>-->
					</tr>
	</thead>
	<tbody>
	<?php foreach ($tasks as $task) { 
		# Access whether direct or part of milestone
		$detail = !empty($task['Task']) ? $task['Task'] : $task;
		# Need to save to different var, so Module, etc still accessible.
		?>
		<tr>
			<td><?php echo $this->Html->link($detail['title'],array('action'=>'edit',$detail['id'])); ?>&nbsp;</td>
			<td>
				<?php echo $this->Html->link($task['Module']['title'], array('controller' => 'modules', 'action' => 'edit', $task['Module']['id'])); ?>
			</td>
			<? if(empty($milestone)) { ?>
			<td>
				<?php echo $this->Html->link($task['Milestone']['title'], array('controller' => 'milestones', 'action' => 'edit', $task['Milestone']['id'])); ?>
			</td>
			<? } ?>
			<!--
					<td>
<?php echo $this->Html->link($task['Parent']['title'], array('controller' => 'tasks', 'action' => 'edit', $task['Parent']['id'])); ?>
</td>
					<td>
<?php echo $this->Html->link($task['Release']['title'], array('controller' => 'releases', 'action' => 'edit', $task['Release']['id'])); ?>
</td>
-->
			<td><?php echo h($detail['type']); ?>&nbsp;</td>
			<td><?php echo h($detail['status']); ?>&nbsp;</td>
			<td><?php echo h($detail['priority']); ?>&nbsp;</td>
			<!--<td><?php echo h($detail['description']); ?>&nbsp;</td>-->
			<td><?php echo $this->Time->timeago($detail['created']); ?>&nbsp;</td>
			<td><?php echo h($detail['due_date']); ?>&nbsp;</td>
			<!--<td><?php echo h($detail['closed_date']); ?>&nbsp;</td>-->
		</tr>
	<?php } ?>
	</tbody>
</table>
