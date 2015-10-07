<? $this->layout = 'Core.plain' ?>
<? $this->assign("page_title", count($visits) . " Visits - ".(!empty($when)?$when:"$days days")); ?>
<? $this->start("title_controls"); ?>
<? if(!empty($when)) { ?>
	<?= $this->Html->back("All", array('action'=>'visits')); ?>
<? } ?>
	<?= $this->Html->back("Stats", array('action'=>'index')); ?>
<? $this->end("title_controls"); ?>
<div class='index'>
<table class='table table-striped'>
<tr>
	<th>Start</th>
	<th>End/Time</th>
	<th>Browser</th>
	<th>Location</th>
	<th>Referer</th>
	<th>Pages</th>
	<th>&nbsp;</th>
</tr>
<? foreach($visits as $visit) { 
	$start = $visit['MarketingVisit']['start'];
	$end = $visit['MarketingVisit']['end'];
?>
<tr>
	<td>
		<?= $this->Time->mondyhms($start); ?>
	</td>
	<td>
		<? if(strtotime($start) == strtotime($end)) { ?>
			&ndash;
		<? } else { ?>
			<? $time = $this->Time->timebetween($start, $end); ?>
			<b><?= $time == 'just now' ? ((strtotime($end)-strtotime($start))). " seconds" : $time ?></b>
		<? } ?>
	</td>
	<td class='maxwidth200'>
		<?= $visit['MarketingVisit']['browser'] ?>
	</td>
	<td>
		<?= $ip = $visit['MarketingVisit']['ip'] ?><br/>
		<? $geoip = !empty($ip) ? HostInfo::geoip($visit['MarketingVisit']['ip']) : null; ?>
		<?= !empty($geoip['city']) ? $geoip['city'] : null; ?>
		<?= !empty($geoip['region']) ? $geoip['region'] : null; ?>
		<?= !empty($geoip['country_name']) ? $geoip['country_name'] : null; ?>
	</td>
	<td class='maxwidth400'>
		<?= $this->Text->autolink($visit['MarketingVisit']['referer']) ?>
		<? $refdomain = $visit['MarketingVisit']['refdomain'];?>
		<? if(!empty($refdomain)) { ?>
			<br/>
			<?= $this->Html->glink("remove", "", array("manager"=>1,"plugin"=>"tracker","controller"=>"tracker","action"=>"spam_referer","?"=>array('refdomain'=>$refdomain)),array('confirm'=>"Remove $refdomain?",'class'=>'btn btn-primary')); ?>
		<? } ?>
		<?= !empty($visit['MarketingVisit']['refqs']) ? "<br/>".$visit['MarketingVisit']['refqs'] : null; ?> 
		<?= !empty($visit['MarketingVisit']['refkeywords']) ? "<br/>".$visit['MarketingVisit']['refkeywords'] : null; ?> 
	</td>
	<td>
		<? foreach($visit['MarketingPageView'] as $view) { ?>
			<?= $view['url']; ?><br/>
		<? } ?>
	</td>
	<td class=''>
		<? if(!empty($ip)) { # Cant ban unless known IP ?>
			<?= $this->Html->glink("remove", "", array("manager"=>1,"plugin"=>"tracker","controller"=>"tracker","action"=>"blacklist",$ip),array('confirm'=>"Ban $ip?",'class'=>'btn btn-primary')); ?>
			&nbsp;
		<?  } ?>
		<?= $this->Html->delete("", array("manager"=>1,"plugin"=>"tracker","controller"=>"tracker","action"=>"delete_visit","Marketing",$visit['MarketingVisit']['id']),array('confirm'=>'Remove visit?')); ?>
	</td>
</tr>
<? } ?>
</table>
</div>

