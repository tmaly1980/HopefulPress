<? $this->assign("page_title", "Click on an adoptable to learn more"); ?>
<?
$adoptables = array(
'Davey',
'Mr. Wiggles',
'Jack',
'Machismo',
'Carly',
'Monsta',
'Cutie Pie',
'Crazy Mazie',
'Princess',
'Scourge'
);

$successes = array(
'Rex',
'Tracy',
'Jonesy',
'Chrissy'
);
?>
<div class='italic'><?= count($adoptables) ?> adoptables available</div>
<br/>
<div class='row'>
<? foreach($adoptables as $i=>$name) { ?>
<div class='col-md-3 height150'>
	<div class='height100 width150 clip'>
	<?= $this->Html->link($this->Html->image("/rescue/images/dog$i.jpg", array('class'=>'height100 border')), "/mockup/adopt/view"); ?>
	</div>
	<div>
		<?= $this->Html->link($name, "/mockup/adopt/view"); ?>
	</div>
</div>
<? } ?>
</div>

<h3 id='success'>Success Stories</h3>
<? foreach($successes as $i=>$name) { ?>
<div class='col-md-3 height150'>
	<div class='height100 width150 clip'>
	<?= $this->Html->link($this->Html->image("/rescue/images/dog$i.jpg", array('class'=>'height100 border')), "/mockup/adopt/success"); ?>
	</div>
	<div>
		<?= $this->Html->link($name, "/mockup/adopt/success"); ?>
	</div>
</div>
<? } ?>
