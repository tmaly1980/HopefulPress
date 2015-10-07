<? if($this->Html->manager()) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->edit("Edit Domain", array('manager'=>1,'action'=>'edit')); ?>
<? $this->end("title_controls"); ?>
<? } ?>
<? $this->assign("page_title", "Your Website Address"); ?>
<? $domain = $current_site['Site']['domain']; ?>
<div id="Site_address" class='lightgreybg border padding25 large marginbottom15'>
	<? if(!empty($domain)) { ?>
		http://<?= $domain ?>/
	<? } else { ?>
		http://<?= $current_site['Site']['hostname'] ?>.<?= Configure::read("default_domain"); ?>/
	<? } ?>
</div>

<div id="Site_dns_details" class="margintop15">
	<? if(empty($domain)) { ?>
		<div class='alert alert-info'>
			Your website can have it's own domain name of your choosing, ie <b>http://organizationname.com/</b> instead of a <b>.<?= Configure::read("default_domain"); ?></b> address.
		</div>
		<?if (empty($current_site['Site']['plan']) || empty($current_site['Site']['stripe_id'])) { ?>
		<div class='alert alert-warning'>
			Please <?= $this->Html->link("upgrade to a paid website","/admin/billing"); ?> in order to add your own domain name.
		</div>
		<? } else { ?>
			<?= $this->Html->add("Add your own domain name", array('action'=>'add'), array('Xupdate'=>'Site_dns','class'=>'btn-primary')); ?>
		<? } ?>
	<? } else { ?>
		<?= $this->Html->link("Change domain", array('action'=>'add'), array('Xupdate'=>'Site_dns','class'=>'color green')); ?>
	<? } ?>
</div>

<? if(!empty($domain)) { ?>
<div class='margintop20'>
	<? if(!$dns_complete) { ?>
		<? if(empty($existing_domain)) { # We will register ?>
			<div class='warn'>
				Your domain setup is not complete. Please give us 24-48 hours from the time of registration to properly configure your domain.
			</div>
		<? } else { ?>
			<div class='warn'>
				Your domain setup is not complete. Please visit your domain registrar (where you registered your domain) and update the domain's DNS servers to the following:
			</div>
		
			<table border=1 cellpadding=15 class='lightgreybg' align='center'>
			<tr>
				<th> DNS Server 1: </th>
				<td> ns1.linode.com </td>
			</tr>
			<tr>
				<th> DNS Server 2: </th>
				<td> ns2.linode.com </td>
			</tr>
			</table>
		
			<div class='note'>
				After this is complete, it may take up to 24 hours before your domain name is properly connected to your website.
			</div>
		<? } ?>
		
	</div>
	<? } else { /* no point, confusing. ?>
	<div class='success'>
		Your domain is properly set up and should be working.
	</div>
	<? */ } ?>
</div>
<? } ?>
