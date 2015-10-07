<?
class Tag extends BlogAppModel
{
	var $name = 'Tag';
	var $hasAndBelongsToMany = array(
		'Post'=>array('className'=>'Blog.Post',
			'with'=>"Blog.PostsTag"
		)
	);

}
