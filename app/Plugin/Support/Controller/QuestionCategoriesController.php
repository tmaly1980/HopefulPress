<?php
App::uses('BelongsToController', 'BelongsTo.Controller');
class QuestionCategoriesController extends BelongsToController {
	function edit($id = null)
	{
		if(!empty($this->request->data['Question']['question_category_id']))
		{
			$id = $this->request->data['Question']['question_category_id'];
		}
		return parent::edit($id);
	}

}
