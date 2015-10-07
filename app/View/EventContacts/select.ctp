<?
	#$label_alt = "(or ".$this->Ajax->link("create a new contact",array('action'=>'add'),array('update'=>'EventContact')).")";
	$label_alt = "(or ".$this->Html->link("create new contact",array('action'=>'add'),array('update'=>'EventContact')) . ")";
	if(empty($selected)) { $selected = null; }
	if(!empty($this->data['Event']['event_contact_id'])) { $selected =  $this->data['Event']['event_contact_id']; } # For initial load below.
?>
<script>
function updateEventContactDetails(id)
{
	if(!id) { $('#EventContact_details').html(''); return; } 
	$("#EventContact_details").load("/event_contacts/view/"+id);
}
</script>
<?= $this->Form->input("Event.event_contact_id", array('options'=>$eventContacts,'selected'=>$selected,
	'label'=>"Contact person", 'label_alt'=>$label_alt, 'empty'=>'- None -',
	'onChange'=>"updateEventContactDetails(this.value);"
	));
?>
<div id="EventContact_details" class='margintop5'></div>
<?= $this->Html->edit("Edit contact details", array('action'=>'edit'),array('class'=>'dialog post')); # 'post' class should carry over the name from here... ?>
<script>
$(document).ready(function() { 
	var selected = $('#EventEventContactId').val();
	if(selected)
	{
		$('#EventContact_details').load("/event_contacts/view/"+selected);
	}
});
</script>
