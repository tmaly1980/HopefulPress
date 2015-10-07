<? $this->assign("page_title", "Projects"); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->add("Add Project", array('admin'=>1,'action'=>'add')); ?>
<? } ?>
<? $this->end("title_controls"); ?>
<? $this->set("crumbs", true); ?>
<div class="projects index">

	<? if(empty($projects)) { ?>
	<div class='nodata'>There are no projects yet.
	</div>
	<? } else { ?>
		<? foreach($projects as $project) { ?>
			<div>
				<div class='left width200'>
					<? if(!empty($project['Project']['page_photo_id'])) { ?>
						<? $photo = $this->Html->image("/page_photos/page_photos/thumb/".$project['Project']['page_photo_id']); ?>
						<?= $this->Html->link($photo, array('action'=>'view',$project['Project']['idurl']), array()); ?>
					<? } ?>
				</div>
				<div class='wrap'>
					<?= $this->Html->link($project['Project']['title'], array('action'=>'view','project_id'=>$project['Project']['idurl']), array('class'=>'medium')); ?>
					<p>
						<?= $this->Html->summary($project['Project']['content']); ?>
					</p>
				</div>
			</div>
		<? } ?>
	<? } ?>

</div>
