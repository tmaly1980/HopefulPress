<!-- might be able to migrate 'adding' to use this modal too... -->
<? $adding = empty($this->data['EventContact']['id']); ?>
<?#= $this->HpModal->set_title($adding?"Add Contact Details":"Update Contact Details"); ?>
<?
	$label_alt = null;
	if(!empty($eventContacts))
	{
		$label_alt = "<br/>(or ".$this->Html->link("select existing contact",array('action'=>'select'),array('update'=>'EventContact')).")";
	}

?>
<!-- we have to add a contact WITH the event, since we cant handle nested forms -->
<div class="EventContact">
<!-- FIREFOX doesnt like ajax form inside larger form.... -->
	<?= $this->Form->hidden('EventContact.id'); ?>
	<?= $this->Form->input('EventContact.name', array('label'=>'Contact name', 'label_alt'=>$label_alt)); ?>

	<?= $this->Html->link("Add contact details", array('action'=>'edit'),array('class'=>'dialog post')); ?>

	<div class="clear"></div>
</div>
