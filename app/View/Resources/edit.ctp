<?= $this->element("Scraper.js"); ?>
<? $pid = Configure::read("project_id"); ?>

<? $id = !empty($this->request->data["Resource"]["id"]) ? $this->request->data["Resource"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Resource Details" : "Add Resource"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if(!empty($pid)) { ?>
	<?= $this->Html->blink("back", "View Project", "/project/$pid"); ?>
<? } else { ?>
	<?= $this->Html->blink("back", "View Resources Page", "/resources"); ?>
<? } ?>
<? $this->end(); ?>
<div class="resources form col-md-6">
<?php echo $this->Form->create('Resource'); ?>
	<?= $this->Form->hidden('id'); ?>
	<?= $this->Form->hidden('project_id'); ?>
	<?= $this->Form->input('title',array('id'=>'ResourceTitle','label'=>'Resource Name','class'=>'required')); ?>
	<?= $this->Form->input('address',array('label'=>'Physical/Mailing Address')); ?>
	<?= $this->Form->input('phone'); ?>
	<?= $this->Form->input('url',array('id'=>'ResourceUrl', 'label'=>'Website Address (URL)','placeholder'=>'http://www.example.com/')); ?>

	<? if(empty($pid)) { ?>
	<div id="ResourceCategory">
		<?= $this->requestAction("/resource_categories/select",array('return')); ?>
	</div>
	<? } ?>
	<?= $this->Form->input('description',array('type'=>'textarea','cols'=>45,'rows'=>5,'class'=>'')); ?>
	<script>
		//j('#ResourceDescription').maxlength();
	</script>
	<?= $this->Form->save('Save Resource');#, array('modal'=>true)); ?>
<?php echo $this->Form->end(); ?>
</div>
