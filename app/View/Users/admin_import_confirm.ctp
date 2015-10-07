<? $this->assign("container_class", "maxwidth700"); ?>
<? $this->assign("page_title", "Confirm User Import"); ?>

<div class='users form border lightgreybg '>
	<?= $this->Form->create("User",array('autocomplete'=>'off','id'=>"UserForm")); ?>
		<? $this->Form->hidden('confirm', array('value'=>1)); ?>

		<table width="100%" class='table table-striped table-bordered'>
		<tr>
			<th>#</th>
			<th>Email</th>
			<th>Last Name</th>
			<th>First Name</th>
			<th>Processor?</th>
			<th>Grower(s)</th>
			<th>
				<?= $this->input("User.SignupNotifyAll",array('id'=>'SignupNotifyAll','label'=>false)); ?>
				Email Notify?</th>
		</tr>
		<? for($i = 0; $i < count($this->data['User']); $i++) { ?>
		<tr>
			<td><?= $i+1 ?></td>
			<td>
				<?= $this->input("User.$i.Email",array('label'=>false)); ?>
			</td>
			<td>
				<?= $this->input("User.$i.LastName",array('label'=>false)); ?>
			</td>
			<td>
				<?= $this->input("User.$i.FirstName",array('label'=>false)); ?>
			</td>
			<td>
				<?= $this->input("User.$i.Processor",array('label'=>false)); ?>
			</td>
			<td>
				<?= $this->Form->input("User.$i.Grower.Grower", array('options'=>$growers,'between'=>'<div class="clear"></div>','size'=>8)); ?>
				<script>
					$('#User<?=$i?>GrowerGrower').tagselect({selectText: 'Select Grower(s)'});
				</script>
			</td>
			<td>
				<?= $this->input("User.$i.SignupNotify",array('label'=>false)); ?>
			</td>
		</tr>
		<? } ?>

		<?= $this->Form->save("Import Users"); ?>
	<?= $this->Form->end(); ?>
	<div class='clear'></div>
</div>
