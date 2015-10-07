<?
App::uses("SupportAppModel", "Support.Model");

class QuestionQuestionTag extends SupportAppModel
{
	var $belongsTo  = array(
		"Question"=>array('className'=>"Support.Question"),
		"QuestionTag"=>array('className'=>"Support.QuestionTag"),
	);

}
