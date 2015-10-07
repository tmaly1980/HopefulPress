<?
App::uses('ForumAppModel', 'Forum.Model');
class Discussion extends ForumAppModel
{
	public $order = 'Discussion.modified DESC';

	public $belongsTo = array(
		'Owner'=>array('className'=>'User','foreignKey'=>'user_id'),
		#'Topic'=>array('className'=>'Forum.Topic'),
	);

	public $hasMany = array(
		'Message'=>array(
			'className'=>'Forum.Message'
		),
	);

}
