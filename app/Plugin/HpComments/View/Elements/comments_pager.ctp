<div class="pager">
<? if(!empty($this->Paginator)) { ?>
<?
$pager_params = $this->Paginator->params();
?>
<? if((empty($this->params['requested']) || !empty($nav)) && empty($more)) { ?>
<!--
<div>
	<?= $this->Paginator->counter(array('format'=>"%current% records")); ?>
</div>
-->
<? if(!empty($counter)) { 
	if($counter === true)
	{
		$Type = $this->Model->singleHumanName();
		$counter = "$Type %page% of %pages%";
	}

?>
<div class="left">
	<?= $this->Paginator->counter(array(
		'format'=>$counter)); ?>
</div>
<? } ?>

<div class="right">

	<? if($this->Paginator->hasPrev()) { ?>
		<?php echo $this->Paginator->prev('< ' . __('previous', true), array(), null, array('class'=>'disabled'));?> &nbsp;
	<? } else if (!empty($loop)) { ?>
		<? $pageCount = $pager_params['pageCount']; ?>
		<?php echo $this->Paginator->link("< previous", array('page'=>$pageCount)); ?>&nbsp;
	<? } ?>

	<? if(!isset($numbers) || $numbers !== false) { ?>
	<?php echo $this->Paginator->numbers();?>
	<? } ?>

	<? if($this->Paginator->hasNext()) { ?>
		&nbsp;<?php echo $this->Paginator->next("next >", array(), null, array('class' => 'disabled'));?>
	<? } else if (!empty($loop)) { ?>
		&nbsp;<?php echo $this->Paginator->link("next >", array('page'=>1)); ?>
	<? } ?>

</div>
<div class="clear"></div>
<? } else if ($this->Paginator->hasNext()) {  # Show more link when in a widget (not a dedicated page)
	$types = $this->Model->things();
	$controller = $this->params['controller'];
?>
	<div align="right">
		<?= $html->link("more $types &raquo;", array($this->params['prefix']=>true, 'controller'=>$controller,'action'=>'index'),array('escape'=>false)); ?>
	</div>
<? } ?>

<? } ?>
<div class="clear"></div>
</div>
