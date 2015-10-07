<div class="eventContacts index">
	<h2><?php __('Event Contacts');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('site_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('address');?></th>
			<th><?php echo $this->Paginator->sort('address2');?></th>
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
	foreach ($eventContacts as $eventContact):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $eventContact['EventContact']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($eventContact['Site']['title'], array('controller' => 'sites', 'action' => 'view', $eventContact['Site']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($eventContact['User']['name'], array('controller' => 'users', 'action' => 'view', $eventContact['User']['id'])); ?>
		</td>
		<td><?php echo $eventContact['EventContact']['name']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['address']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['address2']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['city']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['state']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['zip_code']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['country']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['phone']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['comments']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['created']; ?>&nbsp;</td>
		<td><?php echo $eventContact['EventContact']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $eventContact['EventContact']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $eventContact['EventContact']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $eventContact['EventContact']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $eventContact['EventContact']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Event Contact', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Sites', true), array('controller' => 'sites', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Site', true), array('controller' => 'sites', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Events', true), array('controller' => 'events', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event', true), array('controller' => 'events', 'action' => 'add')); ?> </li>
	</ul>
</div>
