<?
	#$label_alt = "(or ".$this->Ajax->link("create a new location",array('action'=>'add'),array('update'=>'EventLocation')).")";
	$label_alt = "(or ".$this->Html->link("create new location",array('action'=>'add'),array('update'=>'EventLocation')) . ")";
	if(empty($selected)) { $selected = null; }
	if(!empty($this->data['Event']['event_location_id'])) { $selected =  $this->data['Event']['event_location_id']; } # For initial load below.
?>
<script>
function updateEventLocationDetails(id)
{
	if(!id) { $('#EventLocation_details').html(''); return; } 
	$("#EventLocation_details").load("/event_locations/view/"+id);
}
</script>
<?= $this->Form->input("Event.event_location_id", array('options'=>$eventLocations,'selected'=>$selected,
	'label'=>"Event location", 'label_alt'=>$label_alt, 'empty'=>'- None -',
	'onChange'=>"updateEventLocationDetails(this.value);"
	));
?>
<div id="EventLocation_details" class='margintop5'></div>
<?= $this->Html->edit("Edit location details", array('action'=>'edit'),array('class'=>'dialog post')); # 'post' class should carry over the name from here... ?>
<script>
$(document).ready(function() { 
	var selected = $('#EventEventLocationId').val();
	if(selected)
	{
		$('#EventLocation_details').load("/event_locations/view/"+selected);
	}
});
</script>
