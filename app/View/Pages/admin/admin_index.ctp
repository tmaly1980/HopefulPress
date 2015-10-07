<? $this->assign("page_title", "Pages"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->blink("add", "Add Page", array("action"=>"add")); ?>
<? $this->end(); ?>
<div class="pages index">

			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('title'); ?></th>
						<th><?php echo $this->Paginator->sort('user_id'); ?></th>
						<th><?php echo $this->Paginator->sort('url'); ?></th>
						<th><?php echo $this->Paginator->sort('content'); ?></th>
								</tr>
				</thead>
				<tbody>
				<?php foreach ($pages as $page) { ?>
					<tr>
						<td><?php echo h($page['Page']['title']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($page['User']['full_name'], array('controller' => 'users', 'action' => 'view', $page['User']['id'])); ?>
		</td>
						<td><?php echo h($page['Page']['url']); ?>&nbsp;</td>
						<td><?php echo h($page['Page']['content']); ?>&nbsp;</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

			<?= $this->element("Core.pager");?>

</div><!-- end containing of content -->
