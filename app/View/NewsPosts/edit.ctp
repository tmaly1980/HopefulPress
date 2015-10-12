<? $pid = Configure::read("project_id"); ?>
<?= $this->element("Core.js/editor"); ?>

<?#= $this->Admin->unloadWarn(); ?>
<? $id = !empty($this->request->data['NewsPost']['id']) ? $this->request->data['NewsPost']['id'] : null ?>
<? #$project_title = $this->Admin->project('title'); ?>
<?= $this->assign("page_title", !empty($id) ? 'Edit News Post'.(!empty($project_title)?" From $project_title":"") : 'Add News Post'.(!empty($project_title)?" To $project_title":"")); ?>
<? $this->start("admin_controls"); ?>
	<? if(empty($id)) { ?>
		<?= $this->Html->back("All News Posts", array("prefix"=>false,"action"=>"index")); ?>
	<? } else { ?>
		<?= $this->Html->back("View News Post", array("prefix"=>false,"action"=>"view",$id)); ?>
	<? } ?>
<? $this->end(); ?>

<div class="newsPosts form">
<?php echo $this->Form->create('NewsPost'); ?>
		<?= $this->Form->title(); ?>
	<?php
		echo $this->Form->hidden('id');
		echo $this->Form->hidden('draft_id');
		echo $this->Form->hidden('project_id');
	?>
	<?#= $this->Form->input("created",array('size'=>'12','type'=>'text','placeholder'=>'mm/dd/yyyy','label'=>'Post Date','default'=>date("m/d/Y"),'class'=>'datepicker','div'=>'form-inline')); ?>
	<div class='clear'></div>
	<div class='row'>
		<div class='col-md-3 right'>
			<?= $this->element("PagePhotos.edit",array()); ?>
		</div>
		<div class='col-md-9'>
			<?#= $this->Slug->slug('url', array('prefix'=>'news')); ?>
			<?= $this->element("owner"); ?>
			<?= $this->Form->content(); ?>

			<table width='100%'><tr><td>
			<? if(!empty($id)) { ?>
				<?= $this->Html->delete("Delete News Post", array("action"=>"delete",$this->data["NewsPost"]["id"]), array("confirm"=>"Are you sure you want to delete this news post?")); ?>
			<? } ?>
			</td><td align="right">
				<?#= $this->Form->save(!$id?"Preview News Post":"Update News Post"); ?>
				<?= $this->Form->save(!$id?"Save News Post":"Update News Post"); ?>
			</td></tr></table>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
</div>
