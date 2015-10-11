<? $pid = Configure::read("project_id"); ?>
<?= $this->element("Core.js/editor"); ?>

<? $id = !empty($this->request->data["FosterPage"]["id"]) ? $this->request->data["FosterPage"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Foster Page" : "Add Foster Page"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->blink("back", "View Page", array("action"=>"view",$id)); ?>
<? } else { ?>
	<?= $this->Html->blink("back", "All Foster Pages", array("action"=>"index")); ?>
<? } ?>
<? $this->end(); ?>

<div class="pages form">

			<?php echo $this->Form->create('FosterPage', array('role' => 'form')); ?>

					<?php echo $this->Form->hidden('id'); ?>

				<div class='row'>

				<div class='col-md-6'>
					<?= $this->Form->title(); ?>
					<?#= $this->Slug->slug('url', array('prefix'=>'page')); ?>
					<?#= $this->element("owner"); ?>


					<?
					/*
					$parents = array();
					if($this->Html->is_admin()) { $parents[''] = '[Topics]'; }
					foreach($topics as $pid=>$ptitle) { $parents[$pid] = $ptitle; }
					if($this->Html->is_admin() && !Configure::read("project_id") && !Configure::read("members_only")) { 
						$parents['0'] = '[Other Pages]';
					}
					foreach($other_pages as $pid=>$ptitle) { $parents[$pid] = $ptitle; }
					*/
					?>

					<?#= $this->Form->input("parent_id", array('label'=>'Parent Page', 'options'=>$parents)); ?>
				</div>
				<div class='col-md-6'>
					<?= $this->element("PagePhotos.edit"); ?>
				</div>

				</div>

					<?= $this->Form->content(); ?>
					<?#= $this->Publishable->autosave(); ?>
				<table width='100%'><tr><td>
				<? if(!empty($id)) { ?>
					<?= $this->Html->delete("Delete Page", array("action"=>"delete",$this->data["FosterPage"]["id"]), array("confirm"=>"Are you sure you want to delete this page?")); ?>
				<? } ?>
				</td><td align="right">
					<?= $this->Form->save(!$id?"Create Page":"Update Page"); ?>
				</td></tr></table>
			<?php echo $this->Form->end() ?>

			<div class='clear'></div>
			<script>
			</script>
</div>
