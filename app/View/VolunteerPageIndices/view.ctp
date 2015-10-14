<? $index = $volunteerPageIndex; ?>
<?= $this->assign("page_title", !empty($index['VolunteerPageIndex']['title']) ? $index['VolunteerPageIndex']['title'] : "Volunteer Information"); ?>
<? if(empty($index['VolunteerPageIndex']['id'])) { ?>
<div class='dashed alert alert-info'>
	Volunteer information and forms are not currently available.
	<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->add("Enable page", array('admin'=>1,'action'=>'enable'),array('short'=>false)); ?>
	<? } ?>
</div>
<? } else { ?>
<? if($this->Html->can_edit()) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->edit("Edit introduction", array('admin'=>1,'action'=>'edit')); ?>
	<?= $this->Html->remove("Hide page", array('admin'=>1,'action'=>'disable'),array('confirm'=>'Are you sure you want to hide all volunteer information? You can always enable it again at any time.')); ?>
<? $this->end(); ?>
<? } ?>

<? if(!empty($index['VolunteerPageIndex']['introduction']) || !empty($index['VolunteerPageIndex']['page_photo_id']) || $this->Html->can_edit()) { ?>
<div class='view row'>
	<div class='col-md-4 pull-right'>
		<?= $this->element("PagePhotos.view"); ?>
	</div>

	<div class='col-md-8 push-left padding25'>
		<? if(!empty($index['VolunteerPageIndex']['introduction'])) { ?>
		<div id='VolunteerPageIndex_Introduction' class='medium double'>
			<?= $index['VolunteerPageIndex']['introduction'] ?>
		</div>
		<? } else if($this->Html->me()) { ?>
		<div class='dashed alert alert-info'>
			<?= $this->Html->link("Add an introduction", array('admin'=>1,'action'=>'edit')); ?>
			to describe your volunteer process
		</div>
		<? } ?>
	</div>
</div>
<? } ?>

<? if(!$this->Html->can_edit() && empty($faqs) && empty($pages) && empty($downloads) && empty($nav['volunteerFormEnabled'])) { ?>
<div class='nodata'>
	Sorry, no volunteer information is currently available.
</div>
<? } ?>

<?= $this->element("rescue/page_index_details",array('type'=>'volunteer','form_link'=>false)); ?>

<? } ?>

<?/*

	<br/>

		<div class='center_align'>
			<?= $this->Html->link("Browse our adoptables", array('controller'=>'adoptables'), array('class'=>'btn btn-warning')); ?>
			<br/>
			<br/>
			<?= $this->Html->link("Fill out an volunteer form", array('controller'=>'volunteers','action'=>'add'), array('class'=>'btn btn-warning')); ?>
		</div>
	</div>

	<div class='clear'></div>
</div>

<div class='row'>
<div class='col-md-9'>
<? if($this->Html->can_edit()) { # By default, only owner can add sub page - and then can re-assign ?>
	<div class='right'>
		<? if(count($pages) > 1) { # && $in_admin && $this->Admin->access()) { ?>
			<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Subpage_sorter')); ?>
		<? } ?>
	</div>
<? } ?>

<? if($this->Html->can_edit()) { # By default, only owner can add sub page - and then can re-assign ?>

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

