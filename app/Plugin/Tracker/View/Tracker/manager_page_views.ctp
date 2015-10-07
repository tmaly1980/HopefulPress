<h3><?= $type ?> Page Views - <?= !empty($date) ? date("D M d", strtotime($date)) : "last $days days" ?></h3>
<?  $hostname = ($model == 'Blog' ? "blog" : "www"); ?>
	<table class='table'>
	<tr>
		<th>#</th>
		<th>Page</th>
		<th>URL</th>
		<th>Total Views</th>
	</tr>
	<? $i = 1; foreach($pageViews as $view) { ?>
	<tr>
		<td><?= $i ?></td>
		<td><?= $this->Html->link($view[$model]['title'], "http://{$hostname}.{$default_domain}".$view[$model]['url']); ?></td>
		<td><?= $this->Html->link($view[$model]['url'], "http://{$hostname}.{$default_domain}".$view[$model]['url']); ?></td>
		<td>
			<?= $this->Html->link($view[0]['count'], "/manager/tracker/tracker/page_view/$type".(!empty($date)?"/$date":"")."?href=".$view[$model]['url']); ?>
		</td>
	</tr>
	<? $i++; } ?>
	</table>

