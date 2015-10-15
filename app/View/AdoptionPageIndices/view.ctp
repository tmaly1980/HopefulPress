<? $mode = "adoption"; ?>
<?= $this->assign("page_title", ucwords($mode)); #$adoptionPageIndex['AdoptionPageIndex']['title']); ?>
<? if($this->Html->can_edit()) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->edit("Edit introduction", array('action'=>'edit')); ?>
<? $this->end(); ?>
<? } ?>

<? /* if($this->Html->can_edit()) { ?>
<div class='alert alert-warning'>
	<? if($rescue) { ?>
	Your website is currently operating as an <b>short-term adoption/foster animal rescue</b>. If your organization primarily operates as a <b>sanctuary</b>, providing <b>long-term shelter and care</b> for animals, you can remove adoption forms, listings, and fostering options as a sanctuary organization.
	<?= $this->Html->link("Disable adoption/foster options",array('admin'=>1,'plugin'=>'rescue','controller'=>'adoption_overviews','action'=>'disable_rescue'),array('class'=>'green')); ?>
	<? } else { ?>
	Your website is currently operating as an <b>animal sanctuary</b>. If your organization <b>temporarily</b> fosters and actively finds <b>adoptive forever homes</b> for animals in your care through an adoption process, you can add adoption and fostering options as a rescue organization. 
	<?= $this->Html->link("Enable adoption/foster options",array('admin'=>1,'plugin'=>'rescue','controller'=>'adoption_overviews','action'=>'enable_rescue'),array('class'=>'green')); ?>
	<? } ?>
</div>
<? } */ ?>

<? if(!empty($adoptionPageIndex['AdoptionPageIndex']['introduction']) || !empty($adoptionPageIndex['AdoptionPageIndex']['page_photo_id']) || $this->Html->can_edit()) { ?>
<div class='view row'>
	<div class='col-md-4 pull-right'>
		<?= $this->element("PagePhotos.view"); ?>
	</div>

	<div class='col-md-8 push-left'>
		<? if(!empty($adoptionPageIndex['AdoptionPageIndex']['introduction'])) { ?>
		<div id='AdoptionPageIndex_Introduction'>
			<?= $adoptionPageIndex['AdoptionPageIndex']['introduction'] ?>
		</div>
		<? } else if($this->Html->me()) { ?>
		<div class='dashed alert alert-info'>
			<?= $this->Html->link("Add an introduction", array('user'=>1,'action'=>'edit')); ?>
			to describe your <?= $mode ?> process
		</div>
		<? } ?>
	</div>
</div>
<? } ?>

<?= $this->element("rescue/page_index_details",array('type'=>'adoption','person'=>'adopter','typeName'=>$mode,'form_link'=>true)); ?>

<? /* if($this->Html->me())  { ?>
<div class='right_align'>
	<?= $this->Html->add($rescue?"Add adoptable":"Add animal profile",array('user'=>1,'controller'=>'adoptables','action'=>'edit')); ?>
</div>
<? } */ ?>
<?= $this->element("adoptables"); ?>

