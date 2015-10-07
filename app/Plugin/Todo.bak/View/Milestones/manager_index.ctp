<? $this->assign("page_title", "Milestones"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("add", "")) { ?>
	<?= $this->Html->add("Add Milestone", array("action"=>"add"),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="milestones index">

			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('title'); ?></th>
						<th><?php echo $this->Paginator->sort('release_id'); ?></th>
						<th><?php echo $this->Paginator->sort('description'); ?></th>
						<th><?php echo $this->Paginator->sort('start_date'); ?></th>
						<th><?php echo $this->Paginator->sort('finish_date'); ?></th>
								</tr>
				</thead>
				<tbody>
				<?php foreach ($milestones as $milestone) { ?>
					<tr>
						<td><?php echo h($milestone['Milestone']['title']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($milestone['Release']['title'], array('controller' => 'releases', 'action' => 'view', $milestone['Release']['id'])); ?>
		</td>
						<td><?php echo h($milestone['Milestone']['description']); ?>&nbsp;</td>
						<td><?php echo h($milestone['Milestone']['start_date']); ?>&nbsp;</td>
						<td><?php echo h($milestone['Milestone']['finish_date']); ?>&nbsp;</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

			<?= $this->element("Core.pager");?>

</div><!-- end containing of content -->
