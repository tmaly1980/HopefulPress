<? $this->assign("page_title", "Contact ".$rescue['Rescue']['title']); ?>

<div class="pages form">
	<? if(
		empty($rescue['Rescue']['phone']) &&
		empty($rescue['Rescue']['email']) &&
		empty($rescue['Rescue']['address']) &&
		$this->Html->can_edit()
	) { ?>
		<div  class='alert alert-info'>
			You haven't filled out any contact information for your organization yet.
			<?= $this->Html->edit("Add contact details", array('admin'=>1,'action'=>'edit','rescue'=>$rescuename),array('short'=>false)); ?>
		</div>
	<? } else { ?>
	<div class='row minheight100'>
		<div class='col-md-6'>
			<? if(!empty($rescue['Rescue']['phone'])) { ?>
			<label>Phone: </label> <?= $rescue['Rescue']['phone'] ?><br/>
			<? } ?>
			<? /* if(!empty($rescue['Rescue']['alternate_phone'])) { ?>
			<label>Secondary Phone: </label> <?= $rescue['Rescue']['alternate_phone'] ?><br/>
			<? } ?>
			<? if(!empty($rescue['Rescue']['fax'])) { ?>
			<label>Fax: </label> <?= $rescue['Rescue']['fax'] ?><br/>
			<? } */ ?>
			<? if(!empty($rescue['Rescue']['email'])) { ?>
			<label>Email: </label> <?= $this->Text->autoLinkEmails($rescue['Rescue']['email']) ?><br/>
			<? } ?>
			<?
                        $address = array();
                        if(!empty($rescue['Rescue']['address'])) { $address[] = $rescue['Rescue']['address']; }
                        if(!empty($rescue['Rescue']['city'])) { $address[] = $rescue['Rescue']['city']; }
                        if(!empty($rescue['Rescue']['state'])) { $address[] = $rescue['Rescue']['state']; }
                        if(!empty($rescue['Rescue']['zip_code'])) { $address[] = $rescue['Rescue']['zip_code']; }

                        $addressString = join(", ", $address);
			if(!empty($addressString)) { 
                        	echo "<label>Address: </label> ".$this->Html->maplink($addressString); 
			} ?>
                        <div class='clear'></div>
		</div>
		<div class='col-md-6'>
                        <? /* if(!empty($addressString) && !empty($rescue['Rescue']['show_map'])) { ?>
                                <?= $this->Html->map($addressString,array('zoom'=>17)); ?>
                        <? } */ ?>
		</div>
	</div>
	<? } ?>

	<div class='clear'></div>

	<hr/>

	<? if(!empty($contacts) && $this->Html->can_edit()) { ?>
	<div class='right'>
		<?= $this->Html->add("Add Contact", array('user'=>1,'controller'=>'contacts','action'=>'add'),array('short'=>false)); ?>
		<? if(count($contacts) > 1) { ?>
		<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'contact_sorter')); ?>
		<? } ?>
	</div>
	<? } ?>

	<? if(!empty($contacts) || $this->Html->can_edit()) { ?>
	<h3 id='Rescue_ContactsTitle'>Contacts</h3>
	<div class='clear'></div>
	<? } ?>

	<? if(empty($contacts)) { ?>
		<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->add("Add Contact", array('admin'=>1,'controller'=>'contacts','action'=>'add','rescue'=>$rescuename),array('short'=>false)); ?>
		<div class='alert alert-info'>
			You can add individual contacts with their own phone and email details
		</div>
		<? } ?>
	<? } else { ?>
	<div id="RescueContacts">
		<?= $this->element("../Contacts/list"); ?>
	</div>
	<? } ?>

	<? if($this->Html->can_edit() && count($contacts) > 1) { ?>
	<script>
	$('#contacts_sorter').sorter('.contactlist',{axis: 'y',controller: 'contacts'});
	</script>
	<? } ?>
</div>
