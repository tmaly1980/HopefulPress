<? $id = !empty($this->request->data["VolunteerFaq"]["id"]) ? $this->request->data["VolunteerFaq"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Volunteer Question" : "Add Volunteer FAQ"); ?></h3>
<div class="links form col-md-9">
<?php echo $this->Form->create('VolunteerFaq'); ?>
	<?= $this->Form->hidden('id'); ?>
	<?= $this->Form->title('question',array('placeholder'=>'Ask a question...')); ?>
	<?= $this->Form->input('answer',array('type'=>'textarea','rows'=>5,'class'=>'','required'=>1)); ?>
	<?= $this->Form->save('Save Question');#, array('modal'=>true)); ?>
<?php echo $this->Form->end(); ?>
</div>
