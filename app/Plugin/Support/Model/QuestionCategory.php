<?
App::uses("SupportAppModel", "Support.Model");

class QuestionCategory extends SupportAppModel
{
	var $actsAs = array('BelongsTo.BelongsTo');

	var $displayField = 'title';
	var $hasMany  = array(
		"Question"=>array('className'=>"Support.Question"),
	);


}
