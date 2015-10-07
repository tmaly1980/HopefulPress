<div class="photos index">
	<h2><?php echo __('Photos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('site_id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('photo_url'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('path'); ?></th>
			<th><?php echo $this->Paginator->sort('thumb_path'); ?></th>
			<th><?php echo $this->Paginator->sort('caption'); ?></th>
			<th><?php echo $this->Paginator->sort('filename'); ?></th>
			<th><?php echo $this->Paginator->sort('ext'); ?></th>
			<th><?php echo $this->Paginator->sort('type'); ?></th>
			<th><?php echo $this->Paginator->sort('size'); ?></th>
			<th><?php echo $this->Paginator->sort('ix'); ?></th>
			<th><?php echo $this->Paginator->sort('photo_album_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($photos as $photo): ?>
	<tr>
		<td><?php echo h($photo['Photo']['id']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['site_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($photo['User']['id'], array('controller' => 'users', 'action' => 'view', $photo['User']['id'])); ?>
		</td>
		<td><?php echo h($photo['Photo']['photo_url']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['title']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['path']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['thumb_path']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['caption']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['filename']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['ext']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['type']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['size']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['ix']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($photo['PhotoAlbum']['title'], array('controller' => 'photo_albums', 'action' => 'view', $photo['PhotoAlbum']['id'])); ?>
		</td>
		<td><?php echo h($photo['Photo']['created']); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $photo['Photo']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $photo['Photo']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $photo['Photo']['id']), null, __('Are you sure you want to delete # %s?', $photo['Photo']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Photo'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Photo Albums'), array('controller' => 'photo_albums', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Photo Album'), array('controller' => 'photo_albums', 'action' => 'add')); ?> </li>
	</ul>
</div>
