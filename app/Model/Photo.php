<?php
App::uses('AppModel', 'Model');
/**
 * Photo Model
 *
 * @property Site $Site
 * @property User $User
 * @property PhotoAlbum $PhotoAlbum
 * @property AboutPage $AboutPage
 * @property Event $Event
 * @property Homepage $Homepage
 * @property NewsPost $NewsPost
 * @property Page $Page
 * @property Page $Page
 */
class Photo extends AppModel {
	var $actsAs = array('Core.Upload');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		/*
		'PhotoAlbum' => array(
			'className' => 'PhotoAlbum',
			'foreignKey' => 'photo_album_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
		*/
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
	);

}
