<? $this->assign("page_title", "Session Details - ".$session_id); ?>
<div>
	<table class='table'>
	<tr>
		<th>IP Address / Host:</th>
		<td><?
			echo  $ip = !empty($blogVisits[0]['BlogVisit']['ip']) ? $blogVisits[0]['BlogVisit']['ip'] : $marketingVisits[0]['MarketingVisit']['ip']; 
			if($hostname = gethostbyaddr($ip))
			{
				echo "<br/>$hostname";
			}
		?></td>
	</tr>
	<tr>
		<th>Browser</th>
		<td>
		<?
			echo $browser = !empty($blogVisits[0]['BlogVisit']['browser']) ? $blogVisits[0]['BlogVisit']['browser'] : $marketingVisits[0]['MarketingVisit']['browser']; 
		?>
		</td>
	</tr>
	</table>

	<? if(!empty($blogVisits)) { ?>
	<h3>Blog Visits</h3>
	<?= $this->element("Tracker.visits",array('visits'=>$blogVisits,'type'=>'Blog')); ?>
	<? } ?>

	<? if(!empty($blogPageViews)) { ?>
	<h3>Blog Page Views</h3>
	<?= $this->element("Tracker.page_views",array('pageViews'=>$blogPageViews,'type'=>'Blog')); ?>
	<? } ?>

	<hr/>

	<? if(!empty($marketingVisits)) { ?>
	<h3>Marketing Visits</h3>
	<?= $this->element("Tracker.visits",array('visits'=>$marketingVisits,'type'=>'Marketing')); ?>
	<? } ?>

	<? if(!empty($marketingPageViews)) { ?>
	<h3>Marketing Page Views</h3>
	<?= $this->element("Tracker.page_views",array('pageViews'=>$marketingPageViews,'type'=>'Marketing')); ?>
	<? } ?>
</div>
