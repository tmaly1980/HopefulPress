<script>$.dialogtitle("Event Contact Details"); </script>
<? $adding = empty($this->data['EventContact']['id']); ?>
<?#= $this->HpModal->set_title($adding?"Add Contact Details":"Update Contact Details"); ?>
<div class="EventContact form width350 fit">
<!-- FIREFOX doesnt like ajax form inside larger form.... -->
<?= $this->Form->create("EventContact", array('update'=>'EventContact','class'=>'ajax','data-update'=>'EventContact')); ?>
	<?= $this->Form->hidden('id'); ?>
	<?= $this->Form->input('name', array('label'=>'Contact name')); ?>

	<?= $this->Form->input('phone'); ?>
	<?= $this->Form->input('email'); ?>
	<?= $this->Form->input('comments'); ?>

	<div class="clear"></div>
	<br/>
	<?= $this->Form->save('Save Contact');#, array('modal'=>true)); ?>
<?= $this->Form->end(); ?>
</div>
