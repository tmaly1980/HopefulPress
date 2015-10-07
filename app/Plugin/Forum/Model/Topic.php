<?
App::uses('ForumAppModel', 'Forum.Model');
class Topic extends ForumAppModel
{
	var $actsAs = array('Sortable.Sortable'); 

	$hasMany = array(
		'Discussion'=>array(
			'className'=>'Forum.Discussion'
		),
	);
	
}
