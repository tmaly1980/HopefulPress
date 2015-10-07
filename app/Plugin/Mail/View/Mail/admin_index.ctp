<? $this->start("title_controls"); ?>
<? if($this->Site->get("email_enabled")) { ?>
	<?= $this->Html->remove("Disable Email Services", array('action'=>'enable')); ?>
<? } ?>
<? $this->end(); ?>
<? $this->assign("page_title", "Email Services"); ?>
<? $domain = $this->Site->get("domain"); # No domain yet. ?>
<? $exampleDomain = !empty($domain) ? $domain : "YourOrganization.org"; ?>
<div class='index'>

<? if(!$this->Site->get("email_enabled")) { ?>
	<div class='alert alert-info marginbottom25'>
		We offer email services so you can have your own email addresses with your domain (username@<?= $exampleDomain ?>).
		<br/>
		<br/>
		Individual users can have their own email inboxes (ie mary@<?= $exampleDomain ?>) accessible via a webmail interface, as well as share email addresses (ie volunteer@<?= $exampleDomain ?>).
		<br/>
		<br/>
		You can also create virtual email addresses (ie volunteer@<?= $exampleDomain ?>) that auto-forward to one or more Yahoo, Gmail, Hotmail, etc email addresses.
	</div>

	<? if(!$domain) { ?>
	<div class='alert alert-warn'>
		Please <?= $this->Html->add("Add a domain name", "/admin/dns"); ?> before enabling email services
	</div>
	<? } ?>
	<?= $this->Html->add("Enable Email Services", array('action'=>'enable')); ?>

		<!-- TODO if they need to use an external mail server, ie yahoo/gmail business, add link for editing 'mail' CNAME/A record -->
	<? } else { #  Enabled ?>

		<div class='alert alert-info'>
			To add/update email addresses for individual users, you can add/remove usernames (username@<?=$domain?>) via the <?= $this->Html->link("Users", "/admin/users"); 
			?><?= !empty($nav['fosterEnabled']) ? ", ".$this->Html->link("Fosters", "/admin/fosters"):null;
			?><?= !empty($nav['volunteerEnabled']) ? ", ".$this->Html->link("Volunteers", "/admin/volunteers"):null;
			?>
			pages
		</div>

		<br/>

		<div class='right'>
			<?= $this->Html->add("Add email account", array('controller'=>'mail_users','action'=>'add')); ?>
		</div>
		<h3>Email Accounts</h3>
		<div class='clear'></div>
		<div class='alert alert-info'>
			Distinct email addresses with separate inboxes, not specific to individual persons, such as 'volunteer@<?=$domain?>'.
		</div>
		<div>
			<? if(empty($mailUsers)) { ?>
			<div class='nodata'>No email accounts yet</div>
			<? } else { ?>
			<table class='table'>
			<? foreach($mailUsers as $user) { ?>
			<tr>
				<td>
					<?= $this->Html->link("{$user['MailUser']['username']}@$domain", array('controller'=>'mail_users','action'=>'edit',$user['MailUser']['id'])); ?>
					<?= $this->Html->edit("Edit", array('controller'=>'mail_users','action'=>'edit',$user['MailUser']['id']),array('class'=>'btn-xs')); ?>
				</td>
			</tr>
			<? } ?>
			</table>
			<? } ?>
		</div>

		<br/>

		<div class='right'>
			<?= $this->Html->add("Add email forward", array('controller'=>'mail_aliases','action'=>'add')); ?>
		</div>
		<h3>Email Forwarders</h3>
		<div class='clear'></div>
		<div class='alert alert-info'>
			Email forwarders let you auto-forward emails sent to a central address (ie 'volunteer@<?=$domain ?>') to one or more third-party addresses (such as @yahoo.com, @gmail.com, etc).
		</div>
		<div>
			<? if(empty($aliases)) { ?>
			<div class='nodata'>No email forwarders yet</div>
			<? } else { ?>
			<table class='table'>
			<? foreach($aliases as $user) { ?>
			<tr>
				<td>
					<?= $this->Html->link("{$user['MailAlias']['alias']}@$domain", array('controller'=>'mail_aliases','action'=>'edit',$user['MailAlias']['id'])); ?>
					<?= $this->Html->edit("Edit", array('controller'=>'mail_aliases','action'=>'edit',$user['MailAlias']['id']),array('class'=>'btn-xs')); ?>
				</td>
			</tr>
			<? } ?>
			</table>
			<? } ?>
		</div>
	<? } ?>
</div>
