<!-- make it long, then give them the control to remove sections if they think it's too long and don't need certain parts -->
<!-- just go with what I have and ask if anything missing, that should add... -->
<?#= $this->element("Core.js/formbuilder"); ?>
<? $this->assign("page_title", $adoptionForm['AdoptionForm']['title']); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("Adoption Information", array('admin'=>false,'action'=>'index')); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Customize Form",array('user'=>1,'controller'=>'adoption_forms','action'=>'edit')); ?>
<? } ?>
<? $this->end("title_controls");  ?>
		<? if(!empty($adoptable)) { ?>
		<div class='alert alert-info medium'>
			Interested in adopting <?= $adoptable ?> or another adoptable?
		</div>
		<? } ?>
<div class='form '>
	<p id='AdoptionForm_introduction'>
		<?= nl2br($adoptionForm['AdoptionForm']['introduction']) ?>
	</p>

		<?= $this->Form->create("Adopter",array('url'=>array('action'=>'add'))); ?>


		<!-- simplify by using re-usable chunks! -->
		<? if($this->Html->can_edit()) { ?>
		<?= $this->Form->input("status",array('options'=>$statuses)); ?>
		<? } ?>

		<?= $this->element("forms/referral"); ?>
		<?= $this->element("forms/about"); ?>
		<?= $this->element("forms/home"); ?>

		<?= $this->element("forms/custom"); ?>
		<!-- XXX TODO custom fields section; custom header; yesno, radio, checkboxes, single line or multiline -->

		<?= $this->element("forms/adoptable"); # ISO ?>

		<?= $this->element("forms/history"); ?>
		<?= $this->element("forms/responsibility"); ?>
		<?= $this->element("forms/references"); ?>

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
