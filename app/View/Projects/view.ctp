<? $in_admin = $this->Html->can_edit(); ?>
<? $this->assign("page_title", !empty($project['Project']['title']) ? $project['Project']['title'] : "Unnamed Project"); ?>

<? $pid = $project['Project']['id']; ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->is_project_admin()) { ?>
	<?= $this->Html->edit("Edit", array('admin'=>1,'action'=>'edit',$pid)); ?>
	<?= $this->Html->blink('user',"Users", array('admin'=>1,'action'=>'users','project_id'=>$pid),array('title'=>'Manage users with project access')); ?>
<? } ?>
<? $this->end("admin_controls"); ?>


<? $this->set("crumbs", "Project Details"); ?>
<? $this->set("share", true); ?>
<? #$this->layout = 'default' ?>
<div class="projects view fontify <?#= $this->Admin->fontsize('default'); ?>">

<!--
<div class="paddingtop10 small">
	<? if($project['Project']['created'] != $project['Project']['modified']) { ?>
		Updated: <?= $this->Time->mondyear($project['Project']['modified']); ?>
	<? } else { ?>
		<?= $this->Time->mondyear($project['Project']['created']); ?>
	<? } ?>
</div>
-->

	<div class='col-md-9'>
	
		<?= $this->element("PagePhotos.view",array('wh'=>'200x200')); ?>
	
		<div class="padding10 minheight100">
			<?= ($project['Project']['content']); ?>
		</div>
	</div>
	<div class='col-md-9'>
	
		<? if(!empty($updates['pages']) || !empty($in_admin)) { # Allow for pages when unpublished - kinda important to project value ?>
		<div class='minheight200'>
			<? if($this->Html->can_edit($project)) { ?>
			<div class='right'>
				<?= $this->Html->madd("Add Page", array('admin'=>1,'controller'=>'pages','action'=>'add','project_id'=>$pid)); ?>
				<? if(count($updates['pages']) > 1) { # && $in_admin && $this->Admin->access()) { ?>
					<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Pages_sorter')); ?>
				<? } ?>
			</div>
			<? } ?>
			<h3 class=''>
				More information
			</h3>
			<div class='clear'></div>
			<div class='margintop10'>
				<? if(!empty($updates['pages'])) { ?>
				<div id="Pages">
					<? foreach($updates['pages'] as $page) { ?>
					<div id="Page_<?= $page['Page']['id'] ?>" class="Page">
						<?= $this->Html->titlelink($page['Page']['title'], array('controller'=>'pages','action'=>'view','project_id'=>$pid,$page['Page']['idurl'])); ?>
						<p class='indent sortable_hide'>
							<?= $this->Html->summary($page['Page']['content']); ?>
						</p>
					</div>
					<? } ?>
				</div>
				<?#= $this->Publishable->unpublishedWarn($updates['pages'], 'pages'); ?>
		
					<br/>
					<br/>
					<?#= $this->Html->add_button("Add page", array('controller'=>'pages','action'=>'add','project_id'=>$pid)); ?>
		
				<? } else { # admin + no pages ?>
				<div class='nodata'>
				There are no pages yet. 
				</div>
				<? } ?>
			</div>
		</div>
		<? } ?>
		
		<? if(count($updates['pages']) > 1 && $this->Html->can_edit($project)) { ?>
		<script>
			$('#Pages_sorter').sorter('.pagelist',{ prefix: "admin" });
		</script>
		<? } ?>
	</div>

	<div class='col-md-12'>
		<?= $this->element("../Homepages/updates",array()); ?>
	</div>
</div>
<div class='clear'></div>

<?/*
<div class='paddingtop25'>

	<div class='col-md-6'>
		<? if(!empty($updates['links']) || $in_admin) { ?>
		<div class='right'>
			<?= $this->Html->smadd('',array('controller'=>'links','action'=>'add','project_id'=>$pid)); ?>
		</div>
		<h3>
			Links
		</h3>
		<div class='clear'></div>
	
		<div id="Links" class='padding5 lightgreybg border'>
			<?= $this->element("../Links/list", array('links'=>$updates['links'])); ?>
		</div>
		<? } ?>
	</div>
	
	<div class='col-md-6'>
		<? if(!empty($updates['downloads']) || $in_admin) { ?>
		<div class='right'>
			<?= $this->Html->smadd('',array('controller'=>'downloads','action'=>'add','project_id'=>$pid)); ?>
		</div>
		<h3>Downloads</h3>
		<div class='clear'></div>
		<div id="Downloads" class='padding5 lightgreybg border'>
			<?= $this->element("../Downloads/list", array('downloads'=>$updates['downloads'])); ?>
		</div>
		<? } ?>
	</div>

</div>
*/ ?>
