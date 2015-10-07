<?
App::uses("SupportAppModel", "Support.Model");

class QuestionTag extends SupportAppModel
{
	var $hasMany = array(
		"QuestionQuestionTag"=>array('className'=>"Support.QuestionQuestionTag"),
	);

	var $hasAndBelongsToMany = array(
		'Question'=>array(
			'className'=>'Support.Question',
			'associationForeignKey'=>"question_id",
			'with'=>'support_question_question_tags',
			'foreignKey'=>"question_tag_id",
		),

	);
}
