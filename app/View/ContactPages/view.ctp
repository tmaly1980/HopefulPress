<? $this->assign("page_title", !empty($contactPage['ContactPage']['title']) ? $contactPage['ContactPage']['title'] : 'Contact Us'); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Edit Page", array("admin"=>1,"action"=>"edit")); ?>
<? } ?>
<? $this->end(); ?>

<?= $this->element("Sortable.js"); ?>


<div class="pages form">
	<? if(
		empty($contactPage['ContactPage']['phone']) &&
		empty($contactPage['ContactPage']['alternate_phone']) &&
		empty($contactPage['ContactPage']['fax']) &&
		empty($contactPage['ContactPage']['email']) &&
		empty($contactPage['ContactPage']['address']) &&
		$this->Html->can_edit()
	) { ?>
		<div  class='alert alert-info'>
			You haven't filled out any contact information for your organization yet.
			<?= $this->Html->edit("Add contact details", array('admin'=>1,'action'=>'edit'),array('short'=>false)); ?>
		</div>
	<? } else { ?>
	<div class='row minheight100'>
		<div class='col-md-6'>
			<? if(!empty($contactPage['ContactPage']['phone'])) { ?>
			<label>Phone: </label> <?= $contactPage['ContactPage']['phone'] ?><br/>
			<? } ?>
			<? if(!empty($contactPage['ContactPage']['alternate_phone'])) { ?>
			<label>Secondary Phone: </label> <?= $contactPage['ContactPage']['alternate_phone'] ?><br/>
			<? } ?>
			<? if(!empty($contactPage['ContactPage']['fax'])) { ?>
			<label>Fax: </label> <?= $contactPage['ContactPage']['fax'] ?><br/>
			<? } ?>
			<? if(!empty($contactPage['ContactPage']['email'])) { ?>
			<label>Email: </label> <?= $this->Text->autoLinkEmails($contactPage['ContactPage']['email']) ?><br/>
			<? } ?>
			<?
                        $address = array();
                        if(!empty($contactPage['ContactPage']['address'])) { $address[] = $contactPage['ContactPage']['address']; }
                        if(!empty($contactPage['ContactPage']['city'])) { $address[] = $contactPage['ContactPage']['city']; }
                        if(!empty($contactPage['ContactPage']['state'])) { $address[] = $contactPage['ContactPage']['state']; }
                        if(!empty($contactPage['ContactPage']['zip_code'])) { $address[] = $contactPage['ContactPage']['zip_code']; }

                        $addressString = join(", ", $address);
			if(!empty($addressString)) { 
                        	echo "<label>Address: </label> ".$this->Html->maplink($addressString); 
			} ?>
                        <div class='clear'></div>
		</div>
		<div class='col-md-6'>
                        <? /* if(!empty($addressString) && !empty($contactPage['ContactPage']['show_map'])) { ?>
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
	<h3 id='ContactPage_ContactsTitle'><?= !empty($contactPage['ContactPage']['contacts_title']) ? $contactPage['ContactPage']['contacts_title'] : "Contacts" ?></h3>
	<div class='clear'></div>
	<? } ?>
	<? if($this->Html->can_edit()) { ?>
	<script>
	$('#ContactPage_ContactsTitle').inline_edit({link: '', inline:'after'});
	</script>
	<? } ?>

	<? if(empty($contacts)) { ?>
		<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->add("Add Contact", array('user'=>1,'controller'=>'contacts','action'=>'add'),array('short'=>false)); ?>
		<div class='alert alert-info'>
			You can add individual contacts with their own phone and email details
		</div>
		<? } ?>
	<? } else { ?>
	<div id="ContactPageContacts">
		<?= $this->element("../Contacts/list"); ?>
	</div>
	<? } ?>

	<? if($this->Html->can_edit() && count($contacts) > 1) { ?>
	<script>
	$('#contacts_sorter').sorter('.contactlist',{axis: 'y',controller: 'contacts'});
	</script>
	<? } ?>
</div>
