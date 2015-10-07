<?
App::uses('BlogAppModel', 'Blog.Model');
class PostsTag extends BlogAppModel
{
	var $name = 'PostsTag';

	var $belongsTo = array(
		'Post'=>array(
			'className'=>'Blog.Post',
		),
		'Tag'=>array(
			'className'=>'Blog.Tag',
		),

	);

}
