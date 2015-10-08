<? $this->set("share",true); ?>
<?# $this->set("crumbs", true); ?>
<? #$project_title = $this->Admin->project('title'); ?>
<? $this->assign("page_title", "News Posts".(!empty($project_title)? " For $project_title":"")); ?>
<? $this->start("admin_controls"); ?>
	<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->add("Add News Post", array("rescuer"=>1,"action"=>"add")); ?>
	<? } ?>
<? $this->end(); ?>

<div class="newsPosts index fontify <?#= $this->Admin->fontsize('default'); ?>">
	<?#= $this->Publishable->admin_filters(); ?>

	<? if(empty($newsPosts)) { ?>
	<div class='nodata'>There are no news posts</div>
	<? } ?>

	<? foreach($newsPosts as $newsPost) { ?>
	<div class="block marginbottom15">
		<div class="left width100 bold">
			<?= $this->Time->mondy($newsPost['NewsPost']['created']); ?>
		</div>
		<div class="wrap">
			<?= $this->Html->titlelink($newsPost['NewsPost']['title'], array('action'=>'view',$newsPost['NewsPost']['idurl']), array()); ?> <?#= $this->Publishable->admin_status($newsPost); ?>
			<div class="">
				<? if($summary = $this->Html->summary($newsPost['NewsPost']['content'])) { ?>
					<?= $summary ?>
					<?= $this->Html->link("Read more...", array('action'=>'view',$newsPost['NewsPost']['idurl']), array()); ?> 
					<?#= $this->Publishable->admin_status($newsPost); ?>
				<? } ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<? } ?>

	<?= $this->element("pager"); ?>

	<?#= $this->Publishable->unpublishedWarn(); ?>

</div>
