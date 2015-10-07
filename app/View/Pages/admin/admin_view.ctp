<? $id = !empty($this->request->data["Page"]["id"]) ? $this->request->data["Page"]["id"] : ""; ?>\n<? $this->assign("page_title", "Page"); ?>\n<? $this->assign("container_class", ""); ?>\n<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>\n\t<?= $this->Html->blink("edit", "Edit Page", array("action"=>"edit",$id)); ?>\n<? $this->end(); ?>\n<div class="pages view">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<tr>
		<th><?php echo __('Title'); ?></th>
		<td>
			<?php echo h($page['Page']['title']); ?>
			&nbsp;
		</td>
</tr>
<tr>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($page['User']['full_name'], array('controller' => 'users', 'action' => 'view', $page['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Url'); ?></th>
		<td>
			<?php echo h($page['Page']['url']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Content'); ?></th>
		<td>
			<?php echo h($page['Page']['content']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($page['Page']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($page['Page']['modified']); ?>
			&nbsp;
		</td>
</tr>
				</tbody>
			</table>
</div>

