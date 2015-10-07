<div class="eventLocations index">
	<h2><?php __('Event Locations');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('site_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('address');?></th>
			<th><?php echo $this->Paginator->sort('city');?></th>
			<th><?php echo $this->Paginator->sort('state');?></th>
			<th><?php echo $this->Paginator->sort('zip_code');?></th>
			<th><?php echo $this->Paginator->sort('country');?></th>
			<th><?php echo $this->Paginator->sort('phone');?></th>
			<th><?php echo $this->Paginator->sort('comments');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($eventLocations as $eventLocation):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $eventLocation['EventLocation']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($eventLocation['Site']['title'], array('controller' => 'sites', 'action' => 'view', $eventLocation['Site']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($eventLocation['User']['name'], array('controller' => 'users', 'action' => 'view', $eventLocation['User']['id'])); ?>
		</td>
		<td><?php echo $eventLocation['EventLocation']['name']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['address']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['city']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['state']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['zip_code']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['country']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['phone']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['comments']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['created']; ?>&nbsp;</td>
		<td><?php echo $eventLocation['EventLocation']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $eventLocation['EventLocation']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $eventLocation['EventLocation']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $eventLocation['EventLocation']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $eventLocation['EventLocation']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Event Location', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Sites', true), array('controller' => 'sites', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Site', true), array('controller' => 'sites', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Events', true), array('controller' => 'events', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event', true), array('controller' => 'events', 'action' => 'add')); ?> </li>
	</ul>
</div>
