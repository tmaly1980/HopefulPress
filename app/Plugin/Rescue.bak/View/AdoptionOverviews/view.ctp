<? $rescue = $this->Site->get("rescue_enabled"); ?>
<? $mode = $rescue?"adoption":"sponsorship" ?>
<? $overview = $adoptionOverview; ?>
<?= $this->assign("page_title", ucwords($mode)); #$overview['AdoptionOverview']['title']); ?>
<? if($this->Html->can_edit()) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->edit("Edit introduction", array('action'=>'edit')); ?>
<? $this->end(); ?>
<? } ?>

<? if($this->Html->can_edit()) { ?>
<div class='alert alert-warning'>
	<? if($rescue) { ?>
	Your website is currently operating as an <b>short-term adoption/foster animal rescue</b>. If your organization primarily operates as a <b>sanctuary</b>, providing <b>long-term shelter and care</b> for animals, you can remove adoption forms, listings, and fostering options as a sanctuary organization.
	<?= $this->Html->link("Disable adoption/foster options",array('admin'=>1,'plugin'=>'rescue','controller'=>'adoption_overviews','action'=>'disable_rescue'),array('class'=>'green')); ?>
	<? } else { ?>
	Your website is currently operating as an <b>animal sanctuary</b>. If your organization <b>temporarily</b> fosters and actively finds <b>adoptive forever homes</b> for animals in your care through an adoption process, you can add adoption and fostering options as a rescue organization. 
	<?= $this->Html->link("Enable adoption/foster options",array('admin'=>1,'plugin'=>'rescue','controller'=>'adoption_overviews','action'=>'enable_rescue'),array('class'=>'green')); ?>
	<? } ?>
</div>
<? } ?>

<? if(!empty($overview['AdoptionOverview']['introduction']) || !empty($overview['AdoptionOverview']['page_photo_id']) || $this->Html->can_edit()) { ?>
<div class='view row'>
	<div class='col-md-4 pull-right'>
		<?= $this->element("PagePhotos.view"); ?>
	</div>

	<div class='col-md-8 push-left'>
		<? if(!empty($overview['AdoptionOverview']['introduction'])) { ?>
		<div id='AdoptionOverview_Introduction'>
			<?= $overview['AdoptionOverview']['introduction'] ?>
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

<?= $this->element("Rescue.overview_details",array('type'=>'adoption','typeName'=>$mode,'form_link'=>true)); ?>

<? if($this->Html->me())  { ?>
<div class='right_align'>
	<?= $this->Html->add($rescue?"Add adoptable":"Add animal profile",array('user'=>1,'plugin'=>'rescue','controller'=>'adoptables','action'=>'edit')); ?>
</div>
<? } ?>
<?= $this->element("adoptables"); ?>



<?/*

	<br/>

		<div class='center_align'>
			<?= $this->Html->link("Browse our adoptables", array('controller'=>'adoptables'), array('class'=>'btn btn-warning')); ?>
			<br/>
			<br/>
			<?= $this->Html->link("Fill out an adoption form", array('controller'=>'adoption_requests','action'=>'add'), array('class'=>'btn btn-warning')); ?>
		</div>
	</div>

	<div class='clear'></div>
</div>

<div class='row'>
<div class='col-md-9'>
<? if($this->Site->can_edit()) { # By default, only owner can add sub page - and then can re-assign ?>
	<div class='right'>
		<? if(count($pages) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>

<? if($this->Site->can_edit()) { # By default, only owner can add sub page - and then can re-assign ?>

	<div class='right'>
		<? if(count($downloads) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>

	<div class='right'>
		<? if(count($faqs) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>

</div>
</div>
<? if($this->Html->can_edit()) { ?>
<script>
$('#Subpage_sorter').sorter('.pagelist',{ prefix: "user" });
</script>
<? } ?>
*/  ?>
