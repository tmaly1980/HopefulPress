<?
$title = $rescue['Rescue']['title']; 
?>
<div class='header-title'>
	<h1>
		<?= $this->Html->link($title, array('plugin'=>null,'prefix'=>null,'controller'=>'rescues','action'=>'view','rescue'=>$rescuename), array('class'=>'')); ?>
	</h1>
</div>
