<? $this->start("title_controls"); ?>
	<?= $this->Html->back("Calendar",array('action'=>'index')); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->add("Add event",array('user'=>1,'action'=>'add')); ?>
<? } ?>
<? $this->end(); ?>
<? $this->assign("page_title", "Events by month"); ?>
<?
	if(empty($year)) { $year = date("Y"); }

	$nextyear = $year+1;
	$prevyear = $year-1;
?>
<div class='index '>
<table border=1 width="100%" cellpadding=5>
<tr>
	<td>
		<div align='center' class='large padding10'>
			<?= $this->Html->blink("chevron-right", "Next", array("action"=>'year',$nextyear), array('title'=>$nextyear, 'class'=>'right btn-primary white')); ?>
			<?= $this->Html->blink("chevron-left", "Previous", array("action"=>'year',$prevyear), array('title'=>$prevyear, 'class'=>'left btn-primary white')); ?>
		
			<?= $year ?>
		</div>
	</td>
</tr>
<? for($month = 1; $month <= 12; $month++) { ?>
<? if(!empty($months[$month-1])) { ?>
<tr>
	<th class='padding5 lightgreybg'>
		<h4>
			<?= $this->Html->link(date("M $year",strtotime("$year-$month-01")), array('action'=>'index',$year,$month),array('class'=>'')); ?>
		</h4>
	</th>
</tr>
<tr>
	<td class='padding25'>
		<?= $this->element("../Events/list",array('events'=>$months[$month-1])); ?>
	</td>
</tr>
<? } ?>
<? } ?>
</table>

</div>
