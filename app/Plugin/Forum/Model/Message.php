<?
App::uses('ForumAppModel', 'Forum.Model');
class Message extends ForumAppModel
{
	public $order = 'Message.created ASC';

	public $belongsTo = array(
		'Discussion'=>array(
			'className'=>'Forum.Discussion'
		),
		'User',
	);
	public $hasMany = array(
		'Like'=>array(
			'className'=>"Forum.MessageLike"
		)
	);
	
}
