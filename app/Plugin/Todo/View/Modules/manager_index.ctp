<? $this->assign("page_title", "Modules"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("add", "")) { ?>
	<?= $this->Html->add("Add Module", array("action"=>"add"),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="modules index">

			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('title'); ?></th>
						<th><?php echo $this->Paginator->sort('description'); ?></th>
								</tr>
				</thead>
				<tbody>
				<?php foreach ($modules as $module) { ?>
					<tr>
						<td><?php echo $this->Html->link($module['Module']['title'], array('action'=>'view',$module['Module']['id'])); ?>&nbsp;</td>
						<td><?php echo h($module['Module']['description']); ?>&nbsp;</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

			<?= $this->element("Core.pager");?>

</div><!-- end containing of content -->
