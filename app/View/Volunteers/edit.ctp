<? # OLD..... maybe convert to volunteer profile signup form???

$id = !empty($this->request->data['Volunteer']['id']) ? $this->request->data['Volunteer']['id'] : null;
<? if(false) {#!$this->Rescue->dedicated()) { # Will have to figure out how to handle dealing with dedicated sites

# ???? how would a volunteer be able to sign-in if not permitted yet on this dedicated site?
# Deny them access to /user/* - but give them a useful error message: 'sorry, only volunteers for RESCUE can do that. If youd like to apply as a volunteer, send a request'
# 
?>
<div class='bold right_align'>
	Already have a Hopeful Press volunteer account? 
	<?= $this->Html->user("Sign in",array('controller'=>'users','action'=>'login')); ?> to use your existing volunteer profile.
</div>
<? } ?>
<!-- make it long, then give them the control to remove sections if they think it's too long and don't need certain parts -->
<!-- just go with what I have and ask if anything missing, that should add... -->
<? $title = !empty($volunteerForm['VolunteerForm']['title']) ? $volunteerForm['VolunteerForm']['title'] : "Volunteer Application"; ?>
<? if($this->request->params['controller'] == 'volunteers') { ?>
	<? $this->assign("page_title", $title); ?>
	<? $this->start("title_controls"); ?>
	<?= $this->Html->back("Volunteer Information", array('admin'=>false,'action'=>'index')); ?>
	<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->edit("Customize Form", array('admin'=>1,'controller'=>'volunteer_forms','action'=>'edit')); ?>
	<? } ?>
	<? $this->end("title_controls"); ?>
<? } else { ?>
	<!--<h3><?= $title  ?></h3>-->
	<? if($this->Html->can_edit()) { ?>
	<div class='right'>
		<?= $this->Html->edit("Customize Form", array('admin'=>1,'controller'=>'volunteer_forms','action'=>'edit'),array('short'=>false)); ?>
	</div>
	<? } ?>
<? } ?>

<div class='form '>
	<p id='VolunteerForm_introduction'>
		<?= nl2br($volunteerForm['VolunteerForm']['introduction']) ?>
	</p>

	<?= $this->Form->create("Volunteer",array('url'=>array('controller'=>'volunteers','action'=>'add'))); ?>
		<?= $this->Form->hidden("id"); ?>

		<?= $this->element("forms/admin_status"); ?>
		<?= $this->element("forms/about"); ?>
		<?= $this->element("forms/home"); ?>
		<?= $this->element("forms/volunteer"); ?>
		<hr/>
	<? if(!empty($volunteerForm['VolunteerForm']['acknowledgment'])){ ?>
	<h3>Acknowledgment/Disclaimer</h3>
	<? } ?>

	<div id='VolunteerForm_acknowledgment'>
		<?= $volunteerForm['VolunteerForm']['acknowledgment'] ?>
	</div>

	<hr/>

		<?= $this->Form->save(!empty($id)?"Update Application":"Submit Application",array('cancel'=>false)); ?>

		<?= $this->Form->end(); ?>
	</div>
</div>
