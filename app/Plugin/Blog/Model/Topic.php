<?php
App::uses('BlogAppModel', 'Blog.Model');
/**
 * BlogPost Model
 *
 */
class Topic extends BlogAppModel {
	var $actsAs = array('Sluggable.Sluggable',
	);

	var $order = 'Topic.ix ASC';

	var $hasMany = array(
		'Posts'=>array('className'=>"Blog.Post",'foreignKey'=>'blog_topic_id','conditions'=>"Posts.parent_id IS NULL")
	);

	var $hasAndBelongsToMany = array(
	);


}
