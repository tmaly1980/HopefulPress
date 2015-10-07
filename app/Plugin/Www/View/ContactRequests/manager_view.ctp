<? $id = !empty($contactRequest["ContactRequest"]["id"]) ? $contactRequest["ContactRequest"]["id"] : ""; ?>
<? $this->assign("page_title", "Contact Request"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<div class="contactRequests view">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<tr>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($contactRequest['ContactRequest']['created']); ?>
			(<?= $this->Time->timeago($contactRequest['ContactRequest']['created']); ?>)
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Request Type'); ?></th>
		<td>
			<?php echo h($contactRequest['ContactRequest']['request_type']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Name'); ?></th>
		<td>
			<?= !empty($contactRequest['User']['id']) ? $contactRequest['User']['full_name'] : $contactRequest['ContactRequest']['name'] ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Email'); ?></th>
		<td>
			<?php echo $this->Text->autoLink(!empty($contactRequest['User']['email'])?$contactRequest['User']['email']:$contactRequest['ContactRequest']['email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Organization'); ?></th>
		<td>
			<?= !empty($contactRequest['Site']['id'])  ? $contactRequest['Site']['title'] : $contactRequest['ContactRequest']['organization'] ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Website'); ?></th>
		<td>
			<?= $this->Text->autolink(!empty($contactRequest['Site']['hostname'])?"http://{$contactRequest['Site']['hostname']}.$default_domain":$contactRequest['ContactRequest']['website']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Status'); ?></th>
		<td>
			<?php echo h($contactRequest['ContactRequest']['status']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Message'); ?></th>
		<td>
			<?php echo nl2br($contactRequest['ContactRequest']['message']); ?>
			&nbsp;
		</td>
</tr>
</tbody>
</table>
</div>

