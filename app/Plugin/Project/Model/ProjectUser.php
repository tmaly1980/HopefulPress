<?php
App::uses('AppModel', 'Model');
class ProjectUser extends AppModel {

	public $belongsTo = array(
		'User'=>array(
			'foreignKey'=>'user_id'
		),
		'Project'=>array(
			'className'=>'Project.Project',
			'foreignKey'=>'project_id'
		)
	);

	public $hasAndBelongsToMany = array(
	);

	public $hasMany = array(
	);

}
