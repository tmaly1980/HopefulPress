<? $this->assign("page_title", "Websites"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add website", array('action'=>'add')); ?>
<? $this->end(); ?>

<div class='sites index'>
	<table class='table table-responsive'>
	<tr>
		<th>Name</th>
		<th>Address</th>
		<th>Site Owner</th>
		<th>Status</th>
		<th>Created</th>
	</tr>
	<? foreach($rescues as $rescue) { $hostname = $rescue['Rescue']['hostname']; ?>
	<tr>
		<td>
			<?= $this->Html->link($rescue['Rescue']['title'], array('action'=>'edit',$rescue['Rescue']['id'])); ?>
		</td>
		<td>
			<?= $this->Html->link("http://$hostname.$default_domain/",null,array('target'=>'_new')); ?>
		</td>
		<td>
			<?= $this->Html->link($rescue['Owner']['name'], "mailto:{$rescue['Owner']['email']}"); ?>
		</td>
		<td>
			<?= !empty($rescue['Rescue']['disabled']) ? "Disabled" : "Active" ?>
			<!-- expired, etc -->
		</td>
		<td>
			<?= $this->Time->timeago($rescue['Rescue']['created']); ?>
		</td>
	</tr>
	<? } ?>
	</table>
</div>
