<?
App::uses('ForumAppModel', 'Forum.Model');
class MessageLike extends ForumAppModel
{
	public $belongsTo = array(
		'Message'=>array(
			'className'=>'Forum.Message'
		),
		'User',
	);
}
