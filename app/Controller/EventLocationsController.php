<?php
App::uses('BelongsToController', 'BelongsTo.Controller');
class EventLocationsController extends BelongsToController {
	function edit($id = null)
	{
		if(!empty($this->request->data['Event']['event_location_id']))
		{
			$id = $this->request->data['Event']['event_location_id'];
		}
		return parent::edit($id);
	}

}
