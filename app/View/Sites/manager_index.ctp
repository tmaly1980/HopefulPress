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
	<? foreach($sites as $site) { $hostname = $site['Site']['hostname']; ?>
	<tr>
		<td>
			<?= $this->Html->link($site['Site']['title'], array('action'=>'edit',$site['Site']['id'])); ?>
		</td>
		<td>
			<?= $this->Html->link("http://$hostname.$default_domain/",null,array('target'=>'_new')); ?>
		</td>
		<td>
			<?= $this->Html->link($site['Owner']['name'], "mailto:{$site['Owner']['email']}"); ?>
		</td>
		<td>
			<?= !empty($site['Site']['disabled']) ? "Disabled" : "Active" ?>
			<!-- expired, etc -->
		</td>
		<td>
			<?= $this->Time->timeago($site['Site']['created']); ?>
		</td>
	</tr>
	<? } ?>
	</table>
</div>
