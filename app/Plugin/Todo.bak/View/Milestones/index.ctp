<? $this->assign("page_title", "Milestones"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? #if($this->Site->can("add", "")) { ?>
	<?= $this->Html->add("Add Milestone", array("action"=>"edit"),array("class"=>"btn btn-success")); ?>
	<?= $this->Html->add("Add Task", array("controller"=>"tasks","action"=>"edit"),array("class"=>"btn btn-default")); ?>
<? #} ?>
<? $this->end(); ?>
<div class="milestones index">

			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('title'); ?></th>
						<th><?php echo $this->Paginator->sort('release_id'); ?></th>
						<th><?php echo $this->Paginator->sort('status'); ?></th>
						<th><?php echo $this->Paginator->sort('description'); ?></th>
						<th><?php echo $this->Paginator->sort('start_date'); ?></th>
						<th><?php echo $this->Paginator->sort('finish_date'); ?></th>

								</tr>
				</thead>
				<tbody>
				<?php foreach ($milestones as $milestone) { ?>
					<tr>
						<td><?php echo $this->Html->link($milestone['Milestone']['title'],array('action'=>'edit',$milestone['Milestone']['id'])); ?>

							<?= $this->Html->link("Tasks", array('controller'=>'tasks','action'=>'index','#'=>"Milestone_".$milestone['Milestone']['id']),array('class'=>'btn btn-xs btn-success')); ?>
						
						&nbsp;</td>
								<td>
			<?php echo $this->Html->link($milestone['Release']['title'], array('controller' => 'releases', 'action' => 'view', $milestone['Release']['id'])); ?>
		</td>
						<td><?php echo h($milestone['Milestone']['status']); ?>&nbsp;</td>
						<td class='maxwidth500'><?php echo $this->Text->truncate($milestone['Milestone']['description']); ?>&nbsp;</td>
						<td><?php echo h($milestone['Milestone']['start_date']); ?>&nbsp;</td>
						<td><?php echo h($milestone['Milestone']['finish_date']); ?>&nbsp;</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

			<?= $this->element("Core.pager");?>

</div><!-- end containing of content -->
