<div id="ProjectsWidget" class='widget Projects minheight75' cellpadding=10 border=0>
	<h3>Projects</h3>
<div class='items'>
	<? $i = 0; foreach($projects as $project) { ?>
	<div class='ProjectWidgetItem' style="bottom: 0px;">
		<?= $this->Html->link($project['Project']['title'], array('controller'=>'projects','action'=>'view',$project['Project']['idurl']), array('class'=>'bold title')); ?>
		<div class='ProjectWidgetItemDetails'>
			<? if(!empty($project['PagePhoto']['id'])) { ?>
			<div align='center' class="<?= $large ? "height175" : "height125"  ?>">
				<?= $this->Html->link($this->Html->image("/page_photos/thumb/".$project['PagePhoto']['id'].($large ? "/250x150/1" : "/175x100/1" ), array('class'=>'border PagePhoto margin5')), array('controller'=>'projects','action'=>'view',$project['Project']['idurl']), array('class'=>'')); ?>
			</div>
			<? } ?>

			<div class='small single_half'>
				<?= $this->Html->summary($project['Project']['content']); ?>
				<?= $this->Html->link("read more", array('controller'=>'projects','action'=>'view',$project['Project']['idurl']), array('class'=>'more')); ?>
			</div>
		</div>
	</div>
	<? } ?>
</div>
</div>
