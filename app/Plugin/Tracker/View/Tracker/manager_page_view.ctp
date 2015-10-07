<? 
$this->assign("page_title", "$type Page View Details - ".
	(!empty($date) ? $this->Time->mondy($date)." - " : "").
	$pageViews[0][$model]['url']);
$model = "{$type}PageView";
$visitModel = "{$type}Visit";
$hostname = ($type == 'Blog' ? "blog" : "www"); 
$lctype = strtolower($type);
?>
<table class='table'>
<tr>
	<th>Title</th>
	<td><?= $pageViews[0][$model]['title']  ?></td>
</tr>
<tr>
	<th>URL</th>
	<td><?= $this->Html->link("http://{$hostname}.{$default_domain}".$pageViews[0][$model]['url'],null); ?></td>
</tr>
</table>

<? if(empty($date)) { # doesnt quite work, values grouped to wrong hours... not urgent ?>
<?= $this->element("Chart.js"); ?>
<?= $this->Chart->container(); ?>
<script>
	$('#graph .graph').graph('tracker.<?=$model?>',{ plugin: 'tracker', 
		urlappend: "?<?= !empty($date) ? "date=$date&":"" ?>href=<?= $href = $pageViews[0][$model]['url'] ?>" ,
		xlabel: "Page Views",
	<? if(!empty($date)) { # hourly views ?>
		dateFormat: "H",
		xdateformat: "H"
	<? } else { ?>
		dateFormat: "D M d",
		xdateformat: "D M d",
		click: function(i,row) {
			var date = row.x;
			window.location = "/manager/tracker/tracker/page_view/<?=$type?>/"+date+"?href=<?=$href ?>";
		}
		<? } ?>
	} );
</script>
<? } ?>

<h3>Top Referers</h3>
<table class='table'>
<tr>
	<th>#</th>
	<th>Referer</th>
	<th>Total</th>
	<th>%</th>
</tr>
<? $i = 1; foreach($topReferers as $ref=>$total) { ?>
<tr>
	<td><?= $i++ ?></td>
	<td><?= empty($ref) ? "<i>None/Direct</i>" : $ref ?></td>
	<td><?= $total ?></td>
	<td><?= sprintf("%d%%", $total/array_sum($topReferers)*100); ?></td>
</tr>
<? } ?>
</table>

<h3>Top Previous Pages</h3>
<table class='table'>
<tr>
	<th>#</th>
	<th>Referer</th>
	<th>Total</th>
	<th>%</th>
</tr>
<? $i = 1; foreach($topPrevious as $url=>$total) { ?>
<tr>
	<td><?= $i++ ?></td>
	<td><?= empty($url) ? "<i>None/Direct</i>" : $url ?></td>
	<td><?= $total ?></td>
	<td><?= sprintf("%d%%", $total/array_sum($topPrevious)*100); ?></td>
</tr>
<? } ?>
</table>

<h3>Top Next Pages</h3>
<table class='table'>
<tr>
	<th>#</th>
	<th>Referer</th>
	<th>Total</th>
	<th>%</th>
</tr>
<? $i = 1; foreach($topNext as $url=>$total) { ?>
<tr>
	<td><?= $i++ ?></td>
	<td><?= empty($url) ? "<i>None/Dead End</i>" : $url ?></td>
	<td><?= $total ?></td>
	<td><?= sprintf("%d%%", $total/array_sum($topNext)*100); ?></td>
</tr>
<? } ?>
</table>



<h3>Page Views</h3>

<?= $this->element("pager",array('model'=>$model,'counter'=>true)); ?>
<table class='table'>
<tr>
	<th>When</th>
	<th>Session / Browser</th>
	<th>IP/Location</th>
	<th>Referer</th>
	<th>Previous Page</th>
	<th>Next Page</th>
</tr>
<? foreach($pageViews as $pageView) { ?>
<tr>
	<td><?= $this->Time->mondyhm($pageView[$model]['created']) ?></td>
	<td><?= $this->Html->link($pageView[$model]['session_id'], "/manager/tracker/tracker/session/".$pageView[$model]['session_id']); ?>
	</td>
	<td>
		<?= $this->element("Tracker.ip", array('ip'=>$pageView[$model]['ip'])); ?>
	</td>
	<td>
		<?= $pageView[$model]['referer'] ?> 
		(<?= $pageView[$model]['refdomain'] ?> / 
		<?= $pageView[$model]['refqs'] ?>)
	</td>
	<td>
		<?= !empty($pageView['Previous']) ? $pageView['Previous']['url'] : null ?> 
	</td>
	<td>
		<?= !empty($pageView['Next']) ? $pageView['Next']['url'] : null ?> 
	</td>
</tr>
<? } ?>
</table>
<?= $this->element("pager",array('model'=>$model,'counter'=>true)); ?>
