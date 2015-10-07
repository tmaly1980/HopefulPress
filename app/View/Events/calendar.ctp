<? $this->start("title_controls"); ?>
	<? if($this->Html->can_edit()) { ?>
                <?= $this->Html->add("Add Event", array('user'=>1,'action'=>'add'), array()); ?> 
	<? } ?>
	<?= $this->element("../Events/list_toggler"); ?>

<? $this->end(); ?>
<? $this->assign("page_title", "Event Calendar"); ?>
<?
	if(empty($year)) { $year = date("Y"); }
	if(empty($month)) { $month = date("m"); }

	$nextyear = sprintf("%04u", $month == 12 ? $year+1 : $year);
	$nextmonth = sprintf("%02u", $month == 12 ? 1 : $month+1);
	$nextmonthname = date("F Y", strtotime("$nextyear-$nextmonth-01"));

	$prevyear = sprintf("%04u", $month == 1 ? $year-1 : $year);
	$prevmonth = sprintf("%02u", $month == 1 ? 12 : $month-1);
	$prevmonthname = date("F Y", strtotime("$prevyear-$prevmonth-01"));
?>
<div class='index '>
<table border=1 width="100%" cellpadding=5>
<tr>
	<td colspan=7>
		<div align='center' class='large padding10'>
			<?= $this->Html->blink("chevron-right", "Next", array("action"=>'calendar',$nextyear,$nextmonth), array('title'=>$nextmonthname, 'class'=>'right btn-primary white')); ?>
			<?= $this->Html->blink("chevron-left", "Previous", array("action"=>'calendar',$prevyear,$prevmonth), array('title'=>$prevmonthname, 'class'=>'left btn-primary white')); ?>
		
			<?= date("F Y", strtotime("$year-$month-01")); ?>
		
		</div>
	</td>
</tr>
<tr class='greybg'>
	<th>Sunday</th>
	<th>Monday</th>
	<th>Tuesday</th>
	<th>Wednesday</th>
	<th>Thursday</th>
	<th>Friday</th>
	<th>Saturday</th>
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
	?>
	<td class='<?= $date == date('Y-m-d') ? "greybg small" : "" ?>' style="width: 14%;">
		<div class='minheight100'>
		<? if(($w > 0 || $d >= $firstdayofweek) && $day <= $daysofmonth) { ?>
			<div class='bold medium'><?= $day ?></div>
			<div>
				<? if(!empty($days[$day-1])) { ?>
				<? foreach($days[$day-1] as $event) { ?>
				<div>
					<?= $this->Html->link($event['Event']['title'], array('action'=>'view',$event['Event']['id']), array('class'=>'font12')); ?>
				</div>
				<? } ?>
				<? } ?>
			</div>
		<? $day++; } ?>
		</div>
	</td>
	<? } ?>
</tr>
<? } ?>
</table>

</div>
