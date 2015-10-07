<? $hostname = $this->Site->get("hostname"); ?>
<? $this->assign("page_title", "Subscribers"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->link("Sign out of MailChimp",array('action'=>'logout'),array('class'=>'red')); ?>
<? $this->end("title_controls"); ?>
<? if(empty($nav['mailingListEnabled'])) { ?>
	<div class='alert alert-info'>
	Mailing lists, emails and email newsletters can be managed through <?= $this->Html->link("MailChimp","http://www.mailchimp.com/",array('target'=>'_new')); ?>. Emails are free for up to 2,000 subscribers and 12,000 emails per month.
	<br/>
		<?= $this->Html->add("Sign in to existing MailChimp account", array('action'=>'login')); ?>
		<br/>
		<br/>
		<?= $this->Html->link("Create a FREE MailChimp account", "http://mailchimp.com/signup",array('target'=>'_new')); ?> if you do not have one yet.
	</div>
<? } else { ?>
<div class='center_align'>
	<?= $this->Html->edit("Go to MailChimp","https://login.mailchimp.com/",array('class'=>'btn-success')); ?>
	<br/>
	to manage and send email messages
	<br/>
</div>
<div>
<table class='table'>
	<? foreach($lists as $lid=>$subscribers) { ?>
	<div class='right'>
		<?= $this->Html->add("Add Subscriber", array('user'=>1,'action'=>'add',$lid),array('class'=>'btn-xs')); ?>
	</div>
	<h4><?= $list_names[$lid] ?> (<?=$list_totals[$lid] ?> total)</h4>
	<div class='clear'></div>
	<? if(empty($subscribers['members'])) { ?>
		<div  class='nodata'>No members for this list yet</div>
	<? } else { ?>
	<table class='table'>
	<tr>
		<th>Email</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Status</th>
		<th>Date</th>
	</tr>
		<? foreach($subscribers['members'] as $sub) { ?>
		<tr>
			<td> 
				<?#= $this->Html->link($sub['email_address'],array('action'=>'edit',$lid,$sub['id'])); ?> 
				<?= $sub['email_address'] ?>
			</td>
			<td> <?= $sub['merge_fields']['FNAME']; ?> </td>
			<td> <?= $sub['merge_fields']['LNAME']; ?> </td>
			<td> <?= $sub['status'] ?> </td>
			<td> <?= $this->Time->mdyhm($sub['timestamp']); ?> </td>
		</tr>
		<? } ?>
	</table>
	<?} ?>
	<br/>
	<br/>

	<? } ?>

</table>
</div>
<? } ?>
