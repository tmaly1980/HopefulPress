<h3>MailChimp mailing list</h3>
<div class='alert alert-info'>
	Mailing lists, emails and email newsletters can be managed through <?= $this->Html->link("MailChimp","http://www.mailchimp.com/",array('target'=>'_new')); ?>. 
	
	<p>Emails are free for up to 2,000 subscribers and 12,000 emails per month.

</div>
<? if(empty($nav['mailingListEnabled'])) { ?>
	<?= $this->Html->add("Sign in to existing MailChimp account", array('action'=>'mailinglist_login')); ?>
		<br/>
		<br/>
	<?= $this->Html->link("Create a FREE MailChimp account", "http://mailchimp.com/signup",array('target'=>'_new')); ?> if you do not have one yet.

<? } else { ?>
	<?= $this->Html->edit("Go to MailChimp","https://login.mailchimp.com/",array('target'=>'_new','class'=>'btn-success')); ?>
	<br/>
	to manage and send email messages
	<br/>
</div>
	<?= $this->Html->link("Sign out of MailChimp",array('action'=>'mailinglist_logout'),array('class'=>'red')); ?>
<? } ?>
