<? $this->assign("page_title", "Releases"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("add", "")) { ?>
	<?= $this->Html->add("Add Release", array("action"=>"add"),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="releases index">

			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('title'); ?></th>
						<th><?php echo $this->Paginator->sort('description'); ?></th>
						<th><?php echo $this->Paginator->sort('launch_date'); ?></th>
								</tr>
				</thead>
				<tbody>
				<?php foreach ($releases as $release) { ?>
					<tr>
						<td><?php echo h($release['Release']['title']); ?>&nbsp;</td>
						<td><?php echo h($release['Release']['description']); ?>&nbsp;</td>
						<td><?php echo h($release['Release']['launch_date']); ?>&nbsp;</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

			<?= $this->element("Core.pager");?>

</div><!-- end containing of content -->
