<? $id = !empty($release["Release"]["id"]) ? $release["Release"]["id"] : ""; ?>
<? $this->assign("page_title", "Release"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("edit", $release)) { ?>
	<?= $this->Html->edit("Edit Release", array("action"=>"edit",$id),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="releases view">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<tr>
		<th><?php echo __('Title'); ?></th>
		<td>
			<?php echo h($release['Release']['title']); ?>
			&nbsp;
		</td>
</tr>
<tr>
</tr>
<tr>
		<th><?php echo __('Description'); ?></th>
		<td>
			<?php echo h($release['Release']['description']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Launch Date'); ?></th>
		<td>
			<?php echo h($release['Release']['launch_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($release['Release']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($release['Release']['modified']); ?>
			&nbsp;
		</td>
</tr>
				</tbody>
			</table>
</div>

<div class="related row">
	<div class="col-md-12">
	<h3><?php echo __('Related Tasks'); ?></h3>
	<?php if (!empty($release['Task'])): ?>
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
	<?php foreach ($release['Task'] as $task): ?>
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
<div class="related row">
	<div class="col-md-12">
	<h3><?php echo __('Related Milestones'); ?></h3>
	<?php if (!empty($release['Milestone'])): ?>
	<table cellpadding = "0" cellspacing = "0" class="table table-striped">
	<thead>
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Release Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Start Date'); ?></th>
		<th><?php echo __('Finish Date'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"></th>
	</tr>
	<thead>
	<tbody>
	<?php foreach ($release['Milestone'] as $milestone): ?>
		<tr>
			<td><?php echo $milestone['id']; ?></td>
			<td><?php echo $milestone['release_id']; ?></td>
			<td><?php echo $milestone['title']; ?></td>
			<td><?php echo $milestone['description']; ?></td>
			<td><?php echo $milestone['start_date']; ?></td>
			<td><?php echo $milestone['finish_date']; ?></td>
			<td><?php echo $milestone['created']; ?></td>
			<td><?php echo $milestone['modified']; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
<?php endif; ?>

	</div><!-- end col md 12 -->
</div>
