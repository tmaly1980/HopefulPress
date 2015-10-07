<? $this->assign("page_title", "Contact Requests"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? $this->end(); ?>
<div class="contactRequests index">

	<table class='table'>
	<tr>
		<th>Sent</th>
		<th>Request Type</th>
		<th>Email</th>
		<th>Name</th>
		<th>Organization</th>
		<th>Status</th>
	</tr>
	<? foreach($contactRequests as $request) { ?>
	<tr>
		<td>
			<?= $this->Time->timeago($request['ContactRequest']['created']); ?>
		</td>
		<td>
			<?= $request['ContactRequest']['request_type'] ?>
		</td>
		<td>
			<?= $this->Html->link(!empty($request['User']['id']) ? $request['User']['email']:$request['ContactRequest']['email'], array('action'=>'view',$request['ContactRequest']['id'])); ?>
		</td>
		<td>
			<?= !empty($request['User']['id']) ? $request['User']['full_name'] : $request['ContactRequest']['name'] ?>
		</td>
		<td>
			<?= !empty($request['Site']['id'])  ? $request['Site']['title'] : $request['ContactRequest']['organization'] ?>
			<?= $this->Text->autolink(!empty($request['Site']['hostname'])?"http://{$request['Site']['hostname']}.$default_domain":$request['ContactRequest']['website']); ?>
		</td>
		<td>
			<?= $request['ContactRequest']['status'] ?>
		</td>
	</tr>
	<? } ?>
	</table>

</div>
