<?php
App::uses('BlogAppModel', 'Blog.Model');
/**
 * BlogPost Model
 *
 */
class Post extends BlogAppModel {
	var $actsAs = array('Sluggable.Sluggable',
		'Core.Dateable'=>array( # Date conversion.
			'fields'=>array('created'),
			'format'=>"M j, Y", # Jan 13, 2002
		)
	);

	var $order = 'Post.id DESC';

	var $hasMany = array(
		'PostsTag'=>array('className'=>"Blog.PostsTag"),
		'Subposts'=>array('className'=>"Blog.Post",'foreignKey'=>"parent_id")
	);

	var $hasAndBelongsToMany = array(
		'Tag'=>array('className'=>'Blog.Tag',
			'join_table'=>'blog_posts_tag',
			'with'=>'Blog.PostsTag',
			#'unique'=>false # prevent erasing every save
			# We need to wipe out so we remove a tag when not in the list!
		)
	);

	function beforeSave($options = array()) # Restructure tags
	{
		error_log("BEFORE SAVE=".print_r($this->data,true));
		if(!empty($this->data['Tag']['tags']))
		{
			$tags = preg_split("/[, ]+/", $this->data['Tag']['tags']);
			foreach($tags as $_tag)
			{
				$_tag = trim($_tag);
				if($_tag)
				{
					$this->Tag->recursive = -1;
					$tag = $this->Tag->findByName($_tag);
					if(!$tag) // Create.
					{
						$this->Tag->create();
						$tag = $this->Tag->save(array('name'=>$_tag));
						$tag['Tag']['id'] = $this->Tag->id;
						if(empty($tag))
						{
							error_log("The tag $_tag could not be saved...");
							return false;
						}
					}
					if($tag) # Now we can save proper in structure for save()
					{
						$this->data['Tag']['Tag'][$tag['Tag']['id']] = $tag['Tag']['id'];
					}

				}
			}
			error_log("DATA=".print_r($this->data['Tag'],true));
		}

		return true;
	}

}
