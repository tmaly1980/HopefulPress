<h3><?= $type ?> Visits - <?= $days ?> days</h3>
<? 
$model = "{$type}Visit";
?>
<?= $this->element("Chart.js"); ?>
<?= $this->Chart->container("graph_$type"); ?>
<script>
$('#graph_<?= $type ?> .graph').graph('tracker.<?=$model?>', {
	urlappend: "<?= $days ?>",
	plugin: 'tracker',
	xlabel: 'Visits',
	dateFormat: "D M d",
	xdateformat: "D M d",
	click: function(i,row) {
		var date = row.x;
		window.location = "/manager/tracker/tracker/page_views/<?=$type?>/"+date;
	}
});
</script>
