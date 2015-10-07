<?= $this->element("Chart.js"); ?>
<? $this->assign("page_title", "Hopeful Press Websites"); ?>
<div class='index'>

	<?= $this->requestAction("/manager/tracker/tracker/visits_graph/Marketing/7", array('return')); ?>
	<?= $this->requestAction("/manager/tracker/tracker/visits_graph/Blog/7", array('return')); ?>
	
	<?= $this->requestAction("/manager/tracker/tracker/top_visits/Blog/7", array('return')); ?>
	<?= $this->requestAction("/manager/tracker/tracker/page_views/Blog/7", array('return')); ?>
	<!--

	- is my writing any good?

	- top views: how much do each article get read?

	- top shares: which  are  liked enough to share?

	- top comments: which have most comments?

	- avg pages per visit

	- stats per page: how  long on  site, what pages  after, etc.

	**** graphics, about,  etc with articles!  

	**** rss feed

	*** subscribers  (graph  and list)
	-->

	<?= $this->requestAction("/manager/tracker/tracker/top_visits/Marketing/7", array('return')); ?>
	<?= $this->requestAction("/manager/tracker/tracker/page_views/Marketing/7", array('return')); ?>

	<!--
	<h3>Marketing Funnel</h3>
	-->

	<?#= $this->Chart->container(); ?>
	<script>
	/*
	$('#graph .graph').graph('sites.funnel', {
		// stupid colors in popup dont match lineColors - even when default ones.
		lineColors: [
			'#b00', // Blog views
			'#0b0', // Marketing page views
			'#00b', // Signup page view
			'#0bb', // Free trials
			'#bb0', // Registered accounts
			'#b0b', // Paid accounts
		],
		trendLineColors: [ // STUPID BUT NEEDED
			'#b00', // Blog views
			'#0b0', // Marketing page views
			'#00b', // Signup page view
			'#0bb', // Free trials
			'#bb0', // Registered accounts
			'#b0b', // Paid accounts
		]
	});
	*/
	</script>
	<style>
	#graph .graph { height: 450px; }
	</style>

	<h3>All Sites</h3>
	<!-- with stats of usage -->

	<table class='table table-striped'> <!-- oldest first, ie better for evaluating heavier usage -->
	<tr>
		<th>Site</th>
		<th>Created</th>
		<th>Status</th>
		<th>Users / Login</th>
		<th>N/E/PH/PG</th>
		<th>Theme</th>
		<th>Visits/mo</th>
		<th>Page Views/mo</th>
	</tr>
	<? foreach($sites as $site) { 
		$plan = $site['Site']['plan']; 
		$days = ceil((time()-strtotime($site['Site']['created']))/(24*60*60));
	?>
	<tr class='<?= $plan ? 'success' : '' ?> <?= empty($plan) && $days > 30 ? "danger" : "" ?>'>
		<td>
		<?= $this->Html->link($site['Site']['title'], 
			$site['Site']['domain'] ? "http://{$site['Site']['domain']}/" : "http://{$site['Site']['hostname']}.{$default_domain}") ?>
		</td>
		<td>
			<?= $this->Time->timeAgoInWords($site['Site']['created']); ?>
		</td>
		<td>
			<?= !empty($plan) ? $plan : "Free Trial" ; ?>
		</td>
		<td>
			<?= !empty($site['userCount']) ? $site['userCount'] : '-'; ?> /
			<?= !empty($site['lastLogin']) ? $this->Time->timeAgoInWords($site['lastLogin']) : '-'; ?>
		</td>
		<td>
			<?= $site['newsCount'] ?> / <?= $site['eventCount'] ?> / <?= $site['photoCount'] ?> / <?= $site['pageCount'] ?>
		</td>
		<td class="">
			<?= !empty($site['SiteDesign']['theme']) && $site['SiteDesign']['theme'] != 'default' ? $site['SiteDesign']['theme'] : '' ?>
			<!-- changed is good -->
		</td>
		<td>
			<?= !empty($site['visits']) ? $site['visits'] : '-'; ?>
		</td>
		<td>
			<?= !empty($site['pageViews']) ? $site['pageViews'] : '-'; ?>
		</td>
	</tr>
	<? } ?>
	</table>
</div>
