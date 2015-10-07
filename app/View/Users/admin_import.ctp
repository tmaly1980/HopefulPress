<? $this->assign("container_class", "maxwidth700"); ?>
<? $this->assign("page_title", "Import Users"); ?>
<? $this->start("admin_controls"); ?>
	<?= $this->Html->blink('back',"All Users", array('action'=>'index','gid'=>$this->Site->gid())); ?>
<? $this->end(); ?>

<div class='users form border lightgreybg '>
	<?= $this->Form->create("User",array('autocomplete'=>'off','id'=>"UserForm",'type'=>'file')); ?>
		<div class='alert alert-info'>
			Please provide a comma-separated file, with one entry per row/line. 
			The first row/line should be labels for each column, including: 'Email', 'LastName', 'FirstName', 'Processor' (1 = Processor, 0 = Grower), 'SignupNotify' (1 = send email with account instructions)
		</div>
		<?= $this->Form->input('file',array('type'=>'file')); ?>
		<?= $this->Form->save('Continue'); ?>
		<div class='note'>
			You will be able to confirm records before importing
		</div>
	<?= $this->Form->end(); ?>
</div>
