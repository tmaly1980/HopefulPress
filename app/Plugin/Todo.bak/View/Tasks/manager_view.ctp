<? $id = !empty($task["Task"]["id"]) ? $task["Task"]["id"] : ""; ?>
<? $this->assign("page_title", "Task"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("edit", $task)) { ?>
	<?= $this->Html->edit("Edit Task", array("action"=>"edit",$id),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="tasks view">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<tr>
		<th><?php echo __('Title'); ?></th>
		<td>
			<?php echo h($task['Task']['title']); ?>
			&nbsp;
		</td>
</tr>
<tr>
</tr>
<tr>
		<th><?php echo __('Parent'); ?></th>
		<td>
			<?php echo $this->Html->link($task['Parent']['title'], array('controller' => 'tasks', 'action' => 'view', $task['Parent']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Milestone'); ?></th>
		<td>
			<?php echo $this->Html->link($task['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $task['Milestone']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Release'); ?></th>
		<td>
			<?php echo $this->Html->link($task['Release']['title'], array('controller' => 'releases', 'action' => 'view', $task['Release']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Status'); ?></th>
		<td>
			<?php echo h($task['Task']['status']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Priority'); ?></th>
		<td>
			<?php echo h($task['Task']['priority']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Type'); ?></th>
		<td>
			<?php echo h($task['Task']['type']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Description'); ?></th>
		<td>
			<?php echo h($task['Task']['description']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Due Date'); ?></th>
		<td>
			<?php echo h($task['Task']['due_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Resolved'); ?></th>
		<td>
			<?php echo h($task['Task']['resolved']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($task['Task']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($task['Task']['modified']); ?>
			&nbsp;
		</td>
</tr>
				</tbody>
			</table>
</div>

