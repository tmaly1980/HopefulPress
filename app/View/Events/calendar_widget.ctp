<div class='widget margintop10 width200'>

<h3>Event Calendar</h3>
<?
	$calurl = (!empty($this->params['prefix']) ? "/".$this->params['prefix'] : ""). "/events/calendar_widget";
	if(empty($year)) { $year = date("Y"); }
	if(empty($month)) { $month = date("m"); }

	$nextyear = sprintf("%04u", $month == 12 ? $year+1 : $year);
	$nextmonth = sprintf("%02u", $month == 12 ? 1 : $month+1);
	$nextmonthname = date("F Y", strtotime("$nextyear-$nextmonth-01"));

	$prevyear = sprintf("%04u", $month == 1 ? $year-1 : $year);
	$prevmonth = sprintf("%02u", $month == 1 ? 12 : $month-1);
	$prevmonthname = date("F Y", strtotime("$prevyear-$prevmonth-01"));
?>
	<div class='center'>
	<table border=1 width="100%" cellpadding=4 class='whitebg'>
	<tr>
		<td colspan=7>
			<div align='center'>
				<?= $this->Html->link("&raquo; ", array("action"=>'calendar_widget',$nextyear,$nextmonth), array('title'=>$nextmonthname, 'class'=>'right block','update'=>'#TimelineCalendar')); ?>
				<?= $this->Html->link("&laquo; ", array("action"=>'calendar_widget',$prevyear,$prevmonth), array('title'=>$prevmonthname, 'class'=>'left block','update'=>'#TimelineCalendar')); ?>
			
				<?= date("F Y", strtotime("$year-$month-01")); ?>
			
			</div>
		</td>
	</tr>
	<tr class='greybg'>
		<th>Su</th>
		<th>Mo</th>
		<th>Tu</th>
		<th>We</th>
		<th>Th</th>
		<th>Fr</th>
		<th>Sa</th>
	</tr>
	<?
	$daysofmonth = date("t", strtotime("$year-$month-01"));
	$firstdayofweek = date("w", strtotime("$year-$month-01")); # ie 0 = sunday, 6 = saturday
	$day = 1;
	
	?>
	<? for($w = 0; $w < 5 && $day <= $daysofmonth; $w++) { ?>
	<tr>
		<? for($d = 0; $d < 7; $d++) { 
			$date = sprintf("%04u-%02u-%02u", $year, $month, $day);
			$started = (($w > 0 || $d >= $firstdayofweek) && $day <= $daysofmonth);
		?>
		<td align='right' class='<?= $started && $date == date('Y-m-d') ? "greybg" : "" ?> <?= $started && !empty($days[$day]) ? "lightgreybg" : "" ?> darkgrey'>
			<? if($started) { ?>
				<?= !empty($days[$day]) ? $this->Html->link($day, array('action'=>'bydate',$year,sprintf("%02u", $month),sprintf("%02u", $day)), array('class'=>'green bold')) : $day ?>
			<? $day++; } ?>
		</td>
		<? } ?>
	</tr>
	<? } ?>
	</table>
	
	</div>
</div>
<div align='right'>
	<?= $this->Html->link("View Full Calendar &raquo;", array('action'=>'calendar'),array('class'=>'more')); ?>
</div>
