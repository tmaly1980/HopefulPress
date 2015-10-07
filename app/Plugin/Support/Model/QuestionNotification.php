<?
App::uses("SupportAppModel", "Support.Model");

class QuestionNotification extends SupportAppModel
{
	var $belongsTo  = array(
		"Question"=>array('className'=>"Support.Question"),
		"User"
	);

}
