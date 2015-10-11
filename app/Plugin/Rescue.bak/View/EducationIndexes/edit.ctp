<?= $this->element("Core.js/editor"); ?>

<? $id = !empty($this->request->data["EducationIndex"]["id"]) ? $this->request->data["EducationIndex"]["id"] : ""; ?>
<? $this->assign("page_title", "Edit Education Overview"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->blink("back", "View Overview", array("action"=>"view")); ?>
<? $this->end(); ?>

<div class="pages form">

			<?php echo $this->Form->create('EducationIndex', array('role' => 'form')); ?>

					<?php echo $this->Form->input('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>

				<div class='row'>

				<div class='col-md-6'>
					<?= $this->Form->title(); ?>
				</div>
				<div class='col-md-6'>
					<?= $this->element("PagePhotos.edit"); ?>
				</div>

				</div>

					<?= $this->Form->content(); ?>
					<?= $this->Form->save("Update Overview"); ?>
			<?php echo $this->Form->end() ?>

			<div class='clear'></div>
</div>
