<h3><?= $type ?> Top Visits - <?= $days ?> days</h3>
<?  $hostname = ($model == 'Blog' ? "blog" : "www"); ?>
	<table class='table'>
	<tr>
		<th>#</th>
		<th>Session ID</th>
		<th>IP/Location</th>
		<th>Start</th>
		<th>End</th>
		<th>Total Pages</th>
		<th>Time</th>
	</tr>
	<? $i = 1; foreach($visits as $visit) { ?>
	<tr>
		<td><?= $i ?></td>

		<td><?= $this->Html->link($visit[$model]['session_id'], "/manager/tracker/tracker/session/".$visit[$model]['session_id']); ?></td>
		<td>
			<?= $this->element("Tracker.ip",array('ip'=>$visit[$model]['ip'])); ?>
		</td>
		<td>
			<?= $this->Time->mondyhms($visit[$model]['start']); ?>
		</td>
		<td>
			<?= $this->Time->mondyhms($visit[$model]['end']); ?>
		</td>
		<td>
			<?= $visit[$model]['page_views']; ?>
		</td>
		<td>
			<?= $this->Time->timebetween($visit[$model]['start'], $visit[$model]['end'],true); ?>
		</td>
	</tr>
	<? $i++; } ?>
	</table>

