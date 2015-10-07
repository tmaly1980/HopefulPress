<?php
App::uses('BelongsToController', 'BelongsTo.Controller');
class EventContactsController extends BelongsToController {

	function edit($id = null)
	{
		if(!empty($this->request->data['Event']['event_contact_id']))
		{
			$id = $this->request->data['Event']['event_contact_id'];
		}
		return parent::edit($id);
	}

}
