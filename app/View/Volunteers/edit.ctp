<?
$id = !empty($this->request->data['Volunteer']['id']) ? $this->request->data['Volunteer']['id'] : null;
$availabilities = array(
	'Weekends',
	'Weekdays',
	'Mornings',
	'Afternoon',
	'Evenings',
	'Varies'
);
$interests = array(
	'Volunteering',
	'Administration/Clerical',
	'Publicity (fliers, brochures, newsletters)',
	'Medical Team',
	'Fundraising/Events',
	'Grant Writing',
	'Transportation',
	'Adoptions/Consulting',
	'Adoptions/Adoption days',
	'Phones',
	'Other'
);
?>
<? if(true) {#!$this->Rescue->dedicated()) { # Will have to figure out how to handle dealing with dedicated sites

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

	<?= $this->Form->create("Volunteer"); ?>
		<?= $this->Form->hidden("id"); ?>
		<?= $this->element("Rescue.forms/admin_status"); ?>
		<?= $this->element("Rescue.forms/about"); ?>
		<?= $this->element("Rescue.forms/home"); ?>
		<hr/>

		<?= $this->Form->input("Volunteer.data.over_18", array('div'=>'col-md-3','label'=>"I am over 18", "required"=>true, 'type'=>'select','options'=>$this->Form->yesno,'default'=>''));  ?>

		<div class='inline-checkbox'>
		<?= $this->Form->input("Volunteer.availability",array('type'=>'checkbox','legend'=>'I am available (check all that apply):','options'=>array_combine($availabilities,$availabilities))); ?>
		<?= $this->Form->input("Volunteer.interests",array('type'=>'checkbox','legend'=>'I am interested in:','options'=>array_combine($interests,$interests))); ?>
		</div>

		<?= $this->Form->input("Volunteer.experience",array('type'=>'textarea','label'=>'Prior experience with animal welfare (list name and location of organizations, if any)','rows'=>4)); ?>


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
