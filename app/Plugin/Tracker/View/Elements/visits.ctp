<? $model = "{$type}Visit"; ?>
<table class='table'>
<tr>
	<th>Referer</th>
	<th>IP/Location</th>
	<th>Start</th>
	<th>End</th>
	<th>Time</th>
</tr>
<? foreach($visits as $visit) { ?>
<tr>
	<td>
		<?= $visit[$model]['referer']  ?>
		(<?= $visit[$model]['refdomain']  ?> / 
		<?= $visit[$model]['refqs']  ?>)
	</td>
	<td>
		<?= $this->element("Tracker.ip",array('ip'=>$visit[$model]['ip'])); ?>
	</td>
	<td>
		<?= $this->Time->mondyhm($visit[$model]['start']); ?>
	</td>
	<td>
		<?= $this->Time->mondyhm($visit[$model]['end']); ?>
	</td>
	<td>
		<?= $this->Time->timebetween($visit[$model]['end'],$visit[$model]['start'],true); ?>
	</td>
</tr>
<? } ?>
</table>
