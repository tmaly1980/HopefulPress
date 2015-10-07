<?
App::uses("SupportAppModel", "Support.Model");

class QuestionComment extends SupportAppModel
{
	var $belongsTo  = array(
		"Question"=>array('className'=>"Support.Question"),
		"User"
	);

}
