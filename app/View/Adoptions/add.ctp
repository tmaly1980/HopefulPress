<!-- make it long, then give them the control to remove sections if they think it's too long and don't need certain parts -->
<!-- just go with what I have and ask if anything missing, that should add... -->
<?#= $this->element("Core.js/formbuilder"); ?>
<?= $this->assign("page_title", $adoptionForm['AdoptionForm']['title']); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("Adoption Information", array('admin'=>false,'action'=>'index')); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Customize Form",array('user'=>1,'controller'=>'adoption_forms','action'=>'edit')); ?>
<? } ?>
<? $this->end("title_controls");  ?>
<div class='form '>
	<p id='AdoptionForm_introduction'>
		<?= nl2br($adoptionForm['AdoptionForm']['introduction']) ?>
	</p>

		<?= $this->Form->create("Adoption",array('url'=>array('controller'=>'adoptions','action'=>'add'))); ?>

		<!-- simplify by using re-usable chunks! -->
		<? if($this->Html->can_edit()) { ?>
		<?= $this->Form->input("status",array('options'=>$statuses)); ?>
		<? } ?>

		<?= $this->element("Rescue.forms/referral"); ?>
		<?= $this->element("Rescue.forms/about"); ?>
		<?= $this->element("Rescue.forms/home"); ?>

		<?= $this->element("Rescue.forms/custom"); ?>
		<!-- XXX TODO custom fields section; custom header; yesno, radio, checkboxes, single line or multiline -->

		<?= $this->element("Rescue.forms/adoptable"); # ISO ?>

		<?= $this->element("Rescue.forms/history"); ?>
		<?= $this->element("Rescue.forms/responsibility"); ?>
		<?= $this->element("Rescue.forms/references"); ?>

		<? if(!empty($adoptionForm['AdoptionForm']['acknowledgment'])){ ?>
		<h3>Acknowledgment/Disclaimer</h3> 
		<? } ?>    

		<div id='AdoptionForm_acknowledgment'>
			<?= $adoptionForm['AdoptionForm']['acknowledgment'] ?>
		</div>

		<?= $this->Form->save("Submit Application",array('cancel'=>false)); ?>

		<?= $this->Form->end(); ?>
	</div>
</div>
