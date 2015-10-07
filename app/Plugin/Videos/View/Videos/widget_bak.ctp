<? $pid = $this->Admin->project('id'); ?>
<div class='widget margintop10 marginbottom10'>
<h3>
	<?#= $this->Html->add_link(null,'videos','modal'); ?>
Recent Videos</h3>
<div class='clear'></div>
	<? if(empty($videos)) { ?>
		<div class='nodata'>
			There are no videos yet.
			<? if($in_admin && $this->Admin->access()) { ?>
				<?= $this->Html->link("Add the first video", array('controller'=>'videos','action'=>'index','project_id'=>$pid,'?'=>array('add'=>1,'project_id'=>$pid)), array('class'=>'color green')); ?>
			<? } ?>
		</div>
	<? } else { ?>
<div class='items'>
		<? foreach($videos as $video) { ?>
			<?= $this->element("../Videos/list_item", array('video'=>$video['Video'])); ?>
		<? } ?>

		<div class='clear'></div>
</div>

		<div align='right'>
			<?= $this->Html->link("More videos...", array('plugin'=>null,'controller'=>'videos','project_id'=>$pid),array('class'=>'small more')); ?>
		</div>
	<? } ?>
</div>
