<? $this->layout = 'Core.plain' ?>
<?= $this->element("Chart.js"); ?>
<div class='index'>

	<!-- funnel -->
	<div class='right_align'>
		<?= $this->Html->link("Visits", array('action'=>'visits')); ?>
	</div>
	<div id='graph'></div>
	<script>
	$('#graph').graph('www/stats',{
		// fill
		area: true,
		//behaveLikeLine: true, /* false = stacked, can see all data lines on top of each other; no color = 0 */
		parseTime:false,
		// colors
		lineColors: ["#CC0000","#CCAA00","#0000CC", "#00CC00"],
		trendLineColors: ["#CC0000","#CCAA00","#0000CC", "#00CC00"],
		
		// Drill-down to visits for day...
		click: function(i,row) {
			window.location.href = "/www/stats/visits/"+row.x;
		}
	
	});
	</script>
	<div class='clear'></div>

	<!-- A/B testing -->
	<? if(!empty($abresults)) { ?>
	<div>
	<h3>A/B Testing</h3>
	<table class='table table-striped'>
	<? foreach($abresults as $page=>$ab) { ?>
	<tr>
		<th><?= $page ?></th>
		<? foreach($ab as $variant=>$details) { ?>
		<td>
			<div class='bold'><?= $variant ?> (<?= $details['calls'] ?>)</div>
			<? foreach($details['cta'] as $cta=>$count) { ?>
			<i><?= $cta ?>: </i> <?= sprintf("%u%%", $count/$details['calls']*100) ?><br/>
			<? } ?>
		</td>
		<? } ?>
	</tr>
	<? } ?>
	</table>
	</div>
	<? } ?>

	<!-- Effectiveness of pages.... page views, next pages/%, exit % -->
	<div>
	<h3>Page Views/Clicks (<?= $signupCount ?> Signups)</h3>
	<table class='table table-striped'>
	<tr>
		<th>Page</th>
		<th>Views</th>
		<th>Next</th>
	</tr>
	<? foreach($pages as $page=>$details) { ?>
	<tr>
		<th><?= $page ?></th>
		<td><?= empty($details['views']) ? "&ndash;" : $details['views']; ?></td>
		<td>
			<? foreach($details['next'] as $nextPage=>$nextPageCount) { ?>
			<div>
				<?= empty($nextPage) ? "<b>EXIT</b>":$nextPage ?> (<?= sprintf("%.1f%%", $nextPageCount/$details['views']*100); ?>)
			</div>
			<? } ?>
		</td>
	</tr>
	<? } ?>
	</table>
	</div>

	<!-- referrals -->
	<div>
	<h3>Referrals</h3>
	<table class='table'>
	<tr>
		<th>Site</th>
		<th>Day</th>
		<th>Week</th>
		<th>Month</th>
	</tr>
	<? foreach($referrals as $refdomain=>$refcount) { ?>
	<tr>
		<th>
			<?= $this->Html->link(!empty($refdomain) ? $refdomain : "<i>Direct</i>", array('action'=>'visits','?'=>array('refdomain'=>$refdomain))) ?>
		</th>
		<td>
			<?= $refcount['day'] ?>
			<?= $refcount['yesterday'] > 0 ? sprintf("<span class='bold %s'>(%+.1f%%)</span>", ($refcount['day']>$refcount['yesterday']?"green":"red"), ($refcount['day']-$refcount['yesterday'])*100/$refcount['yesterday']) : '-'?>
		</td>
		<td>
			<?= $refcount['week'] ?>
			<?= $refcount['lastweek'] > 0 ? sprintf("<span class='bold %s'>(%+.1f%%)</span>", ($refcount['week']>$refcount['lastweek']?"green":"red"), ($refcount['week']-$refcount['lastweek'])*100/$refcount['lastweek']) : '-'?>
		</td>
		<td>
			<?= $refcount['month'] ?>
			<?= $refcount['lastmonth'] > 0 ? sprintf("<span class='bold %s'>(%+.1f%%)</span>", ($refcount['month']>$refcount['lastmonth']?"green":"red"), ($refcount['month']-$refcount['lastmonth'])*100/$refcount['lastmonth']) : '-'?>
		</td>
	</tr>
	<? } ?>
	</table>
	</div>

	<!-- site stats chart; what do I want to see once I have 10+ customers? To prove merit of product. -->
	<div>
	<h3><?= count($sites) ?> Sites</h3>
	<table class='table'>
	<tr>
		<th>Site</th>
		<th>Days</th>
		<th>Plan</th>
		<th>Adoptables</th>
		<th>A/V/F</th>
		<th>Users</th>
		<th>Features</th>
	</tr>
	<? foreach($sites as $site) { ?>
	<tr>
		<td>
			<? $url = "http://". (!empty($site['domain']) ? $site['domain']:($site['hostname'].".$default_domain")); ?>
			<b><?= $this->Html->link($site['title'],array('manager'=>1,'controller'=>'sites','action'=>'edit',$site['id'])) ?></b>
			<br/>
			<?= $this->Html->link($url); ?>
			<br/>
			<?= $this->Html->link($site['Owner']['name'], "mailto:{$site['Owner']['email']}"); ?>
		</td>
		<td>
			<?= round((time()-strtotime($site['created']))/(60*60*24)); # Days old ?> 
		</td>
		<td>
			<?= empty($site['plan']) ? "-" : $site['plan']; ?>
		</td>
		<td>
			<?= !empty($site['adoptable_count']) ? $site['adoptable_count']: "-"; ?>
		</td>
		<td>
			<?= $site['adoption_count'] ? $site['adoption_count']:"-"; ?>
			/ <?= $site['volunteer_count'] ? $site['volunteer_count']:"-"; ?>
			/ <?= $site['foster_count'] ? $site['foster_count']: "-"; ?>
		</td>
		<td>
			<?= $site['user_count']>1 ? ($site['user_count']-1): "-"; ?>
		</td>
		<td>
			<?= !empty($site['mailing_list_enabled']) ? "Mailing List":null; ?>
			<?= !empty($site['email_enabled']) ? "Webmail":null; ?>
			<?= !empty($site['donation_enabled']) ? "Donations":null; ?>
			<?= !empty($site['foster_enabled']) ? "Foster":null; ?>
			<?= !empty($site['volunteer_enabled']) ? "Volunteer":null; ?>
		</td>
	</tr>
	<? } ?>
	</table>
	</div>
</div>
