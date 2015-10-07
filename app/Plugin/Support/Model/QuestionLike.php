<?
App::uses('SupportAppModel', 'Support.Model');
class QuestionLike extends SupportAppModel
{
	public $belongsTo = array(
		'Question'=>array(
			'className'=>'Support.Question'
		),
		'User',
	);
}
