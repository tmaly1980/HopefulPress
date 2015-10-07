<?
App::uses("SupportAppModel", "Support.Model");

class Question extends SupportAppModel
{
	var $order = "Question.modified DESC";

	var $belongsTo  = array(
		"QuestionCategory"=>array( 'className'=>'Support.QuestionCategory','foreignKey'=>'question_category_id'),
		"Answerer"=>array( 'className'=>'User','foreignKey'=>'answerer_user_id'),
		"User" # Questioner
	);

	var $hasMany = array(
		"QuestionComment"=>array('className'=>'Support.QuestionComment','foreignKey'=>'question_id'),
		"QuestionNotification"=>array('className'=>'Support.QuestionNotification','foreignKey'=>'question_id'),
		'Like'=>array( 'className'=>"Support.QuestionLike",'foreignKey'=>'question_id')
	);

	var $hasAndBelongsToMany = array(
		'QuestionTag'=>array(
			'className'=>"Support.QuestionTag",
			'foreignKey'=>"question_id",
			'with'=>'support_question_question_tags',
			'associationForeignKey'=>"question_tag_id",
		),

	);
}
