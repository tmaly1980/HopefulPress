<? $this->assign("page_title", "Tasks"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("add", "")) { ?>
	<?= $this->Html->add("Add Task", array("action"=>"add"),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="tasks index">

			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('title'); ?></th>
						<th><?php echo $this->Paginator->sort('parent_id'); ?></th>
						<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
						<th><?php echo $this->Paginator->sort('release_id'); ?></th>
						<th><?php echo $this->Paginator->sort('status'); ?></th>
						<th><?php echo $this->Paginator->sort('priority'); ?></th>
						<th><?php echo $this->Paginator->sort('type'); ?></th>
						<th><?php echo $this->Paginator->sort('description'); ?></th>
						<th><?php echo $this->Paginator->sort('due_date'); ?></th>
						<th><?php echo $this->Paginator->sort('resolved'); ?></th>
								</tr>
				</thead>
				<tbody>
				<?php foreach ($tasks as $task) { ?>
					<tr>
						<td><?php echo h($task['Task']['title']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($task['Parent']['title'], array('controller' => 'tasks', 'action' => 'view', $task['Parent']['id'])); ?>
		</td>
								<td>
			<?php echo $this->Html->link($task['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $task['Milestone']['id'])); ?>
		</td>
								<td>
			<?php echo $this->Html->link($task['Release']['title'], array('controller' => 'releases', 'action' => 'view', $task['Release']['id'])); ?>
		</td>
						<td><?php echo h($task['Task']['status']); ?>&nbsp;</td>
						<td><?php echo h($task['Task']['priority']); ?>&nbsp;</td>
						<td><?php echo h($task['Task']['type']); ?>&nbsp;</td>
						<td><?php echo h($task['Task']['description']); ?>&nbsp;</td>
						<td><?php echo h($task['Task']['due_date']); ?>&nbsp;</td>
						<td><?php echo h($task['Task']['resolved']); ?>&nbsp;</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

			<?= $this->element("Core.pager");?>

</div><!-- end containing of content -->
