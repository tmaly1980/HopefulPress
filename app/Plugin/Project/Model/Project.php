<?php

App::uses('AppModel', 'Model');
class Project extends AppModel {

	public $belongsTo = array(
		'User'=>array(
			'className'=>'User',
			'foreignKey'=>'user_id'
		),
		'PagePhoto' => array(
			'className' => 'PagePhotos.PagePhoto',
			'foreignKey' => 'page_photo_id',
		),
	);
	public $hasMany = array(
		'ProjectUser'=>array(
			'className'=>'Project.ProjectUser',
			'foreignKey'=>'project_id'
		),
	);

	public $hasAndBelongsToMany = array(
	);

	var $actsAs = array(
		#'Publishable',
		'Sluggable.Sluggable'
	);

}
