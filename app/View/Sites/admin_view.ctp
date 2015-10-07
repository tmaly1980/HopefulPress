<? $this->assign("page_title", "Website Information / Account"); ?>
<? $this->layout = 'admin'; ?>

<? $this->start("title_controls"); ?>
<? if(empty($current_site['Site']['disabled'])) { ?>
	<?= $this->Html->blink("remove", "Cancel Website", array('action'=>'cancel'), array('class'=>'medium btn-danger')); ?>
<? } ?>
<? $this->end(); ?>

<div class="sites form">
<? if(!empty($current_site['Site']['disabled'])) { ?>
	<p class='bold large red'>Your website is currently disabled</p>

	<p><?= $this->Html->link("Restore Website", array('action'=>'restore'), array('class'=>'color green large')); ?>

<? } else { # Enabled ?>
	<table cellpadding=10>
	<!--
	<tr>
		<th class="width150 right_align">Organization Name</th>
		<td>
			<div id="Site_title">
				<?= $current_site['Site']['title'] ?>
			</div>
			<script>
			$('#Site_title').inline_edit_link({link:"Add name/Edit name",plugin: 'sites'}); 
			</script>
		</td>
	</tr>
	-->
	<? /*
	<tr>
		<th class='right_align' >Account Type</th>
		<td>
			<div id='billing_view'>
				<?= $this->requestAction("/admin/stripe/stripe_billing/view", array('return')); ?>
			</div>
		</td>
	</tr>
	<tr>
		<th class='right_align'>Your Website Address</th>
		<td>
	*/ ?>


	<h3>Website Plan</h3>
	<div>
		<? if(empty($current_site['Site']['plan'])) { $trial_days = (time() - $current_site['Site']['created'])/(24*60*60); ?>
			
			<div class='medium'>Free Trial</div>
			<?= $this->Html->link("Upgrade to a paid account", "/admin/billing",array('class'=>'btn btn-success')); ?>

			<? if($trial_days > Configure::read("trial_days")) { ?>
			<div class='alert alert-warning'>
				Your free trial is expired. Please consider upgrading to a paid account to continue.
			</div>
			<? } ?>
		<? } else { ?>
			<div class='medium'><?= ucwords($current_site['Site']['plan']); ?></div>

			<?= $this->Html->link("Update billing information", "/admin/billing"); ?>
		<? } ?>

	</div>

	<br/>

	<h3>Your Website Address</h3>
		<div id='Site_dns'>
			<?= $this->requestAction("/admin/dns/dns/view", array('return')); # Malysoft will never show 'pending' since fake ?>
		</div>
	
	<? /*
		</td>
	</tr>
<?  if(false) { # DISABLED ?>
	<? if(empty($current_site['Site']['domain'])) { # May be determined by some signup tasks, etc... no direct links. ?>
	<tr>
		<th class='right_align'>Webmail Server</th>
		<td>
			<div class='tip'>In order to have your own webmail access and email@yourdomain.com for your users, you'll need to first register/specify your own domain.</div>
		</td>
	</tr>
	<? } else { ?>
	<? if(!$this->Admin->dns_complete()) { ?>
	<tr>
		<th class='right_align'>Webmail Server</th>
		<td>
			<div class='tip'>Once your domain setup is complete, you'll be able to configure webmail services for your website and give your users their own <i>name@<?= $current_site['Site']['domain'] ?></i> email address.</div>
		</td>
	</tr>
	<? } else { ?>
	<tr>
		<th class='right_align'>Webmail Server</th>
		<td>
			<? if(empty($current_site['Site']['email_enabled'])) { ?>
				<b>DISABLED</b> | 
				<?= $this->Html->link("Enable Email Server", array('action'=>'email_enable'), array('class'=>'color green')); ?>
				<div class='tip margintop25'>
					Once you enable your email server, users will be able to have their own email like <b><i>username@<?= $current_site['Site']['domain'] ?></i></b>.
					<p>Users will be able to check their email via the <b>Staff Login</b> page.
				</div>
			<? } else { ?>
				<b>ENABLED</b> |
				<?= $this->Html->link("Disable Email Server", array('action'=>'email_disable'), array('class'=>'color red','confirm'=>"Are you sure you want to disable your email server? It can always be re-enabled later.")); ?>
				<div class='tip margintop25'>
					Users can have their own email like <b>username@<?= $current_site['Site']['domain'] ?></b>.
					<p>Note: You'll need to create usernames for people from their
					<?= $this->Html->link("User Account Page", "/admin/users", array('class'=>'color')); ?> or tell each user to create their own username the first time they log in to the webmail system, before their email address is ready to receive messages.

					<p>Email can be accessed by users via the <b>Staff Login</b> page or via the <b>Webmail</b> link at the top of the admin page.
					<p>Or mail will be accessed through <b><?= $this->Admin->site_url("/webmail"); ?></b>
				</div>
			<? } ?>

		<? if(!empty($current_site['Site']['email_enabled'])) { ?>
			<? if($this->Admin->current_site("email_register_manual")) { ?>
				<div class='bold font16'>
				Currently, email addresses can only be created by website administrators.
				</div>
				<?= $this->Html->link("Let users create their own email usernames", array('action'=>'email_register_auto'), array('class'=>'color green')); ?> when they first go to their 'Webmail' page.
			<? } else { ?>
				<div class='bold font16'>
				By default, users can create their own email usernames when they first go to their 'Webmail' page.
				</div>
				<?= $this->Html->link("Only let administrators create email usernames", array('action'=>'email_register_manual'), array('class'=>'color red')); ?>. Users won't be able to send/receive email until an administrator creates a username for them.
			<? } ?>
		<? } ?>
		</td>
	</tr>

	<? if(!empty($current_site['Site']['email_enabled'])) { ?>
	<tr>
		<th class="width150 right_align">Email Aliases/ Forwarders</th>
		<td>
			<div id="EmailAliases">
				<?= $this->requestAction(array('plugin'=>null,'controller'=>'email_aliases'), array('return')); ?>
			</div>

		</td>
	</tr>
	<? } ?>
	<? } ?>
	<? } ?>
<? } */ ?>

	</table>
<? } ?>
</div>
