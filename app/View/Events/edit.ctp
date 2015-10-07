<?= $this->element("Core.js/datepicker"); ?>

<?#= $this->Admin->unloadWarn(); ?>
<? $id = !empty($this->data['Event']['id']) ? $this->data['Event']['id'] : null; ?>
<? $location_id = !empty($this->request->data['Event']['event_location_id']) ? $this->request->data['Event']['event_location_id'] : null; ?>
<? $contact_id = !empty($this->request->data['Event']['event_contact_id']) ? $this->request->data['Event']['event_contact_id'] : null; ?>

<? #$project_title = $this->Admin->project('title'); ?>
<? $this->assign("page_title", !empty($id) ? 'Edit Event'.(!empty($project_title)?" From $project_title":"") : 'Add Event'.(!empty($project_title)?" To $project_title":"")); ?>
<? $this->start("admin_controls"); ?>
	<? if(!empty($id)) { ?>
		<?= $this->Html->blink('back', "View Event", array('action'=>'view',$id),array('class'=>'btn-primary')); ?>
		<?= $this->Html->blink('list', "All Events", array('action'=>'index')); ?>
	<? } else { ?>
		<?= $this->Html->back("All Events", array('action'=>'index')); ?>
	<? } ?>
<? $this->end("admin_controls"); ?>
<div class="events form">
<?php echo $this->Form->create('Event'); ?>
	<?= $this->Form->input('id'); ?>
	<?= $this->Form->hidden('project_id'); ?>
	<?= $this->Form->title('title',array('placeholder'=>'New Event')); ?>
	<?
		$start_date = !empty($this->data['Event']['start_date']) ? $this->data['Event']['start_date'] : null;
		$end_date = !empty($this->data['Event']['end_date']) ? $this->data['Event']['end_date'] : null;
		$start_time = !empty($this->data['Event']['start_time']) ? $this->data['Event']['start_time'] : null;
		$end_time = !empty($this->data['Event']['end_time']) ? $this->data['Event']['end_time'] : null;
	?>

	<!-- trying to achieve: 25% start_date, 25% end_date, 50% add pic (move up if need be) -->

<div class='row'>
	<div class='col-md-6 col-md-push-6'>
		<?= $this->element("PagePhotos.edit"); ?>
	</div>
	<div class='col-md-6 col-md-pull-6'>
		<?#= $this->Slug->slug('url', array('prefix'=>'event')); ?>
		<?= $this->element("owner"); ?>
		<div class='col-md-6'>
			<?= $this->Form->date('start_date'); ?>
			<div id='end_date_add' class='end_dates' style="<?= !empty($end_date) && $end_date != $start_date ? "display:none;":""?>">
				<?= $this->Html->link("Add end date", "javascript:void(0)",
					array('onClick'=>"$('.end_dates').toggle(); $('#EventEndDate').val($('#EventStartDate').val()); $('#EventEndDate').change();")); ?>
			</div>
			<div id='end_date' class='end_dates' style="<?= !empty($end_date) && $end_date != $start_date ? "":"display:none;"?>">
				<?= $this->Form->date('end_date'); ?>
			</div>
		</div>
		<div class='col-md-6'>
			<?= $this->Form->time('start_time'); ?>
			<div id='end_time_add' class='end_times' style="<?= !empty($end_time) && $end_time != $start_time ? "display:none;":""?>">
				<?= $this->Html->link("Add end time", "javascript:void(0)",
					array('onClick'=>"$('.end_times').toggle(); $('#EventEndTime').val($('#EventStartTime').val());")); ?>
			</div>
			<div id='end_time' class='end_times' style="<?= !empty($end_time) && $end_time != $start_time ? "":"display:none;"?>">
				<?= $this->Form->time('end_time',array('default'=>'')); ?>
			</div>
		</div>

	</div>
</div>
<div class='row'>
	<div class='col-md-12 margintop25'>
		<?= $this->Form->input('summary',array('label'=>'Summary','placeholder'=>'Event summary...', 'required'=>false)); ?>
		<?= $this->Form->content('details',array('label'=>'Details','placeholder'=>'Event details...', 'required'=>false)); ?>
	</div>
	<div class='clear'></div>

	<div id="EventLocation" class='col-md-6'>
		<?= $this->requestAction("/event_locations/select/$location_id", array('return')); ?>
	</div>
	
	<div id="EventContact" class='col-md-6'>
			<?= $this->requestAction("/event_contacts/select/$contact_id", array('return')); ?>
	</div>
</div>

<div class='col-md-12'>
	<table width="100%">
	<tr>
		<td>
			<? if(!empty($id)) { ?>
				<?= $this->Html->delete("Delete Event", array('action'=>'delete',$id),array('confirm'=>'Are you sure you want to remove this event?')); ?>
			<? } ?>
		</td>
		<td align='right'>
			<?= $this->Form->save($id ? 'Update Event':'Save Event'); ?>
		</td>
	</tr>
	</table>

</div>

<?php echo $this->Form->end(); ?>
</div>
